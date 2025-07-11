<?php

namespace App\Http\Controllers;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Card;

class LessonController extends Controller
{

    public function index() {
        return view('admin.lessons.index', [
            'lessons' => Lesson::all()
        ]);
    }

    public function create() {
        return view('admin.lessons.create', ['cards' => Card::all()]);
    }

    public function store() {
        $lesson = new Lesson;
        $lesson->name = request('lesson_name');
        $lesson->notes = request('lesson_notes');
        $lesson->save();

        //Add new cards
        if($card_ids = request('card_ids')) {
            foreach ($card_ids as $card_id) {
                $card = Card::find($card_id);
                $card->lesson_id = $lesson->id;
                $card->save();
            }
        }

        return redirect("/lessons", 302);
    }

    public function redirect_to_edit(Request $request) {
        $selected_lesson_id = $request->lesson_id;
        return redirect("/lessons/$selected_lesson_id/edit", 302);
    }

    public function edit(Lesson $lesson) {
        return view('admin.lessons.edit', [
            'lesson' => $lesson->load('cards'),
            'cards' => Card::all()
        ]);
    }

    public function update(Lesson $lesson) {
        //Update lesson name
        $lesson->name = request('lesson_name');
        $lesson->notes = request('lesson_notes');
        $lesson->save();

        //Add new cards
        if($card_ids = request('card_ids')) {
            foreach ($card_ids as $card_id) {
                $card = Card::find($card_id);
                $card->lesson_id = $lesson->id;
                $card->save();
            }
        }

        //Remove cards
        if($card_ids = request('remove_cards')) {
            foreach ($card_ids as $card_id) {
                $card = Card::find($card_id);
                $card->lesson_id = null;
                $card->save();
            }
        }

        return redirect("/lessons/$lesson->id/edit", 302);
    }

    public function destroy(Lesson $lesson) {
        $lesson->delete();
        return redirect("/", 302);
    }

    public function show(Lesson $lesson, Request $request) {
        //Get Cards
        $cards = $lesson->cards()->oldest()->get();
        
        //Get current position from session OR set it to 0
        $currentStep = session("lesson_{$lesson->id}_step", 0);

        echo "Current step: " . $currentStep;
        
        //Check lesson completion
        if ($currentStep >= $cards->count()) {
            //Reset session step to 0
            session(["lesson_{$lesson->id}_step" => 0]);

            //Add XP for completing a lesson
            $this->add_xp(Auth::id(), 10);

            return view('last-card-answer', [
                'lesson_id' => $lesson->id
            ]);
        }

        //Get current card
        $currentCard = $cards[$currentStep];

        return view('cards', [
            'lesson_id' => $lesson->id,
            'card' => $currentCard
        ]);
    }

    public function check(Request $request, Lesson $lesson) {
        //Get Cards
        $cards = $lesson->cards()->oldest()->get();
        
        //Get current position from session OR set it to 0
        $currentStep = session("lesson_{$lesson->id}_step", 0);

        $currentCard = $cards[$currentStep];

        //Check if answer is provided
        $validated = $request->validate([
            'answer' => 'required|string'
        ]);

        //Check if the answer was wrong
        if ($currentCard->answer != $validated['answer']) {
            //Lose a heart if the answer was wrong
            $this->lose_a_heart(Auth::id());

            //Send the right answer and user's wrong answer as a flash message
            $answer_status = [
                "right" => false,
                "flash_message" =>
                    "<i>Right answer:</i> " . $currentCard->answer
            ];
        } else {
            //Send the right answer as a flash message
            $answer_status = [
                "right" => true,
                "flash_message" => "<i>Answer:</i> " . $currentCard->answer
            ];
        }

        //Move to next step
        session(["lesson_{$lesson->id}_step" => $currentStep + 1]);

        return redirect("/lessons/$lesson->id", 302)->with('answer_status', $answer_status);
    
    }

    public function post_final_card_answer() {
        //Redirect to main with a congrats message
        return redirect('/')->with('lesson_completed', 'Congratulations! You earned 10 XP for completing this lesson!');
    }

    private function add_xp($user_id, $amount) {
        $user = User::find($user_id);
        $user->xp += $amount;
        $user->save();
    }

    private function lose_a_heart($user_id) {
        $user = User::find($user_id);
        $user->heart = $user->heart - 1;
        $user->save();
    }

    public function notes(Lesson $lesson) {
        return view("notes", ['notes' => $lesson->notes, 'lesson_id' => $lesson->id]);
    }

}