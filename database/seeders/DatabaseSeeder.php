<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Major;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            ContentSeeder::class,
            QuestSeeder::class,
            AchievementSeeder::class,
        ]);

        $this->seedUsers();
    }

    protected function seedUsers(): void
    {
        $medical = Major::first();
        $cities = City::all();

        User::factory()->create([
            'phone_number' => '09123456789',
            'name' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => 'Kakayee',
            'role' => 'admin',
            'major_id' => $medical?->id,
            'xp' => 500,
            'gems' => 500,
        ]);

        $distributed = [
            ['Tehran', 3],
            ['Mashhad', 2],
            ['Isfahan', 2],
            ['Shiraz', 2],
            ['Tabriz', 2],
            ['Karaj', 2],
            ['Ahvaz', 1],
            ['Rasht', 1],
        ];

        foreach ($distributed as [$cityEn, $count]) {
            $city = $cities->first(fn ($c) => $c->getTranslation('name', 'en') === $cityEn);
            if (! $city) {
                continue;
            }

            for ($i = 0; $i < $count; $i++) {
                $user = User::factory()->create([
                    'major_id' => $medical?->id,
                    'country_id' => $city->province->country_id,
                    'province_id' => $city->province_id,
                    'city_id' => $city->id,
                    'xp' => fake()->numberBetween(50, 2500),
                ]);

                // Seed recent xp transactions so weekly/daily leaderboards have data.
                for ($d = 0; $d < 7; $d++) {
                    XpTransaction::create([
                        'user_id' => $user->id,
                        'amount' => fake()->numberBetween(5, 30),
                        'reason' => 'answer_correct',
                        'created_at' => Carbon::now()->subDays($d)->subHours(fake()->numberBetween(1, 20)),
                    ]);
                }
            }
        }
    }
}
