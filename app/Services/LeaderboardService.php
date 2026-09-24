<?php

namespace App\Services;

use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardService
{
    public function get(User $user, string $scope, string $period, int $page = 1): array
    {
        $scope = in_array($scope, ['universal', 'country', 'province', 'city'], true) ? $scope : 'universal';
        $period = in_array($period, ['all_time', 'weekly', 'daily'], true) ? $period : 'all_time';
        $pageSize = config('gamification.leaderboard.page_size', 50);

        if ($period !== 'all_time') {
            return $this->periodLeaderboard($user, $scope, $period, $page, $pageSize);
        }

        $query = User::query()->with('major', 'country', 'province', 'city');
        $this->applyScope($query, $user, $scope);
        $query->orderByDesc('users.xp')->orderBy('users.id');

        $paginator = $query->paginate($pageSize, ['*'], 'page', $page);

        $rankOffset = ($paginator->currentPage() - 1) * $pageSize;
        $entries = collect($paginator->items())->map(function ($entry) use (&$rankOffset) {
            return [
                'rank' => ++$rankOffset,
                'user' => $this->serialize($entry, 'xp'),
            ];
        });

        return $this->buildResponse($user, $scope, $period, $paginator, $entries, $period === 'all_time' ? (int) $user->xp : null);
    }

    protected function periodLeaderboard(User $user, string $scope, string $period, int $page, int $pageSize): array
    {
        $start = $this->periodStart($period);

        $query = XpTransaction::query()
            ->join('users', 'users.id', '=', 'xp_transactions.user_id')
            ->select('xp_transactions.user_id', DB::raw('SUM(xp_transactions.amount) as period_xp'))
            ->where('xp_transactions.created_at', '>=', $start);

        $this->applyScope($query, $user, $scope);

        $query->groupBy('xp_transactions.user_id')
            ->orderByDesc('period_xp')
            ->orderBy('xp_transactions.user_id');

        $paginator = $query->paginate($pageSize, ['*'], 'page', $page);

        $items = collect($paginator->items());
        $scores = $items->pluck('period_xp', 'user_id');

        $userModels = User::query()
            ->with('major', 'country', 'province', 'city')
            ->whereIn('id', $items->pluck('user_id'))
            ->get()
            ->keyBy('id');

        $rankOffset = ($paginator->currentPage() - 1) * $pageSize;
        $entries = $items->map(function ($item) use (&$rankOffset, $scores, $userModels) {
            $model = $userModels->get($item->user_id);

            return [
                'rank' => ++$rankOffset,
                'user' => $model ? $this->serialize($model, 'period_xp', (int) ($scores[$item->user_id] ?? 0)) : null,
            ];
        });

        return $this->buildResponse($user, $scope, $period, $paginator, $entries, $this->periodScore($user, $period));
    }

    protected function buildResponse(
        User $user,
        string $scope,
        string $period,
        $paginator,
        $entries,
        ?int $currentUserScore
    ): array {
        return [
            'scope' => $scope,
            'period' => $period,
            'data' => $entries,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'current_user' => [
                'rank' => $this->rankOf($user, $scope, $period),
                'score' => $currentUserScore ?? 0,
                'user' => $this->serialize($user, $period === 'all_time' ? 'xp' : 'period_xp', $currentUserScore),
            ],
        ];
    }

    protected function applyScope(Builder $query, User $user, string $scope): void
    {
        match ($scope) {
            'country' => $query->where('users.country_id', $user->country_id),
            'province' => $query->where('users.province_id', $user->province_id),
            'city' => $query->where('users.city_id', $user->city_id),
            default => null,
        };
    }

    protected function periodStart(string $period): Carbon
    {
        $now = Carbon::now(config('gamification.streak_timezone'));

        return $period === 'weekly' ? $now->copy()->startOfWeek() : $now->copy()->startOfDay();
    }

    protected function rankOf(User $user, string $scope, string $period): int
    {
        if ($period !== 'all_time') {
            $start = $this->periodStart($period);

            $higher = XpTransaction::query()
                ->join('users', 'users.id', '=', 'xp_transactions.user_id')
                ->where('xp_transactions.created_at', '>=', $start)
                ->select('xp_transactions.user_id', DB::raw('SUM(xp_transactions.amount) as period_xp'))
                ->groupBy('xp_transactions.user_id')
                ->having(DB::raw('SUM(xp_transactions.amount)'), '>', $this->periodScore($user, $period))
                ->pluck('xp_transactions.user_id');

            $query = User::query();
            $this->applyScope($query, $user, $scope);
            $count = $query->whereIn('users.id', $higher)->count();

            return $count + 1;
        }

        $query = User::query();
        $this->applyScope($query, $user, $scope);
        $count = $query->where('users.xp', '>', (int) $user->xp)->count();

        return $count + 1;
    }

    protected function periodScore(User $user, string $period): int
    {
        return (int) XpTransaction::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $this->periodStart($period))
            ->sum('amount');
    }

    protected function serialize(User $user, string $scoreColumn, ?int $scoreOverride = null): array
    {
        $score = $scoreOverride
            ?? ($scoreColumn === 'period_xp' ? (int) ($user->period_xp ?? 0) : (int) $user->xp);

        return [
            'id' => $user->id,
            'name' => $user->name ?: trim(($user->first_name ?? '').' '.($user->last_name ?? '')),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_img_url' => $user->profile_img_url,
            'score' => $score,
            'major' => $user->major ? ['id' => $user->major->id, 'name' => $user->major->name ?? null] : null,
            'country' => $user->country ? ['id' => $user->country->id, 'name' => $user->country->name ?? null] : null,
            'province' => $user->province ? ['id' => $user->province->id, 'name' => $user->province->name ?? null] : null,
            'city' => $user->city ? ['id' => $user->city->id, 'name' => $user->city->name ?? null] : null,
        ];
    }
}
