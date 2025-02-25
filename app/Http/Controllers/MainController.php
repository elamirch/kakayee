<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class MainController extends Controller
{
    public function index() {
        //Find the user's major
        $major_id = Auth::user()->major_id;

        //Get chapters' data
        $selected_course_ID = request('course_prime');
        // dd($selected_course_ID);
        if(!is_null($selected_course_ID)){
            return view('/main', ['major' => $major_id, 'courses' => Course::with(Course::class), 'chapters' => Chapter::with(Course::class)]);
        } else {
            return view('/main', ['major' => Major::with('courses')->find($major_id)]);
        }

        
    }

    public function show() {
        //CHECK IF THE USER HAS THIS LESSON!
        //SELECT THE COURSE THAT HAS BEEN STUDIED AFTER FINISHING IT
        $step = request('step');
        if( $step != null){
            $lesson_cards = json_decode(Lesson::where('id', request('lesson_ID'))->first()->cards);
            if(count($lesson_cards) >= $step) {
                
                if(request('check_answer') != null){
                    
                    //retrieve the card
                    $card_id = $lesson_cards[request('step')-2];
                    $card_answer = Card::where('id', $card_id)->first()->answer;
                    $card_title = Card::where('id', $card_id)->first()->title;
                    $card_content = Card::where('id', $card_id)->first()->content;
                    
                    //Validate answer
                    if ($card_answer != request('answer')) {
                        $user = User::find(Auth::user()->id);
                        $user->heart = $user->heart - 1;
                        $user->save();
                    }
                    //the provided step by notes is for the next card, but here we decrease it to match the current card
                    $step = $step - 1;
                    //return the card to 'notes' view
                    return view("notes", ['lesson_ID'=>request('lesson_ID'), 'card_title' => $card_title, 'card_content' => $card_content, 'step' => $step, 'check_answer' => 'true', 'right_answer'=> $card_answer, 'your_answer' => request('answer')]);
                } else {

                    //retrieve the card
                    $card_id = $lesson_cards[request('step')-1];
                    $card_title = Card::where('id', $card_id)->first()->title;
                    $card_content = Card::where('id', $card_id)->first()->content;

                    //return the card to 'notes' view
                    return view("notes", ['lesson_ID'=>request('lesson_ID'), 'card_title' => $card_title, 'card_content' => $card_content, 'step' => $step]);
                }
            } elseif(count($lesson_cards) < $step) {
                if(request('check_answer') != null){

                    //retrieve the card
                    $card_id = $lesson_cards[request('step')-2];
                    $card_answer = Card::where('id', $card_id)->first()->answer;
                    $card_title = Card::where('id', $card_id)->first()->title;
                    $card_content = Card::where('id', $card_id)->first()->content;

                    //Validate answer
                    if ($card_answer != request('answer')) {
                        $user = User::find(Auth::user()->id);
                        $user->heart = $user->heart - 1;
                        $user->save();
                    }

                    //the provided step by notes is for the next card, but here we decrease it to match the current card
                    $step = $step - 1;

                    //return the card to 'notes' view
                    return view("notes", ['lesson_ID'=>request('lesson_ID'), 'card_title' => $card_title, 'card_content' => $card_content, 'step' => $step, 'check_answer' => 'true', 'right_answer'=> $card_answer, 'your_answer' => request('answer')]);
                } else {

                    //Retrieve courses from the database
                    $major = Auth::user()->major;
                    $courses = '';
                    $course_IDs = json_decode(Major::where('name', $major)->first()->courses);
                    foreach ($course_IDs as $course_ID) {
                        $courses = $courses . $course_ID . ": " . Course::where('id', $course_ID)->first()->name . ", ";
                    }
            
                    //Add to xp
                    $user = User::find(Auth::user()->id);
                    $user->xp = $user->xp + $step - 1;
                    $user->save();

                    //Go to main page
                    return redirect('/')->with(['courses' => $courses]);
                }
            }
        } else {
            return view("notes", ['notes' => Lesson::where('id', request('lesson_ID'))->first()->notes]);
        }
    }

}
