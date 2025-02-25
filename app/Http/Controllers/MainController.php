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
        
        $major = Auth::user()->major;
        //Get the courses and send it to the view
        $course_IDs = json_decode(Major::where('name', $major)->first()->courses);
        $courses = '';
        foreach ($course_IDs as $course_ID) {
            $courses = $courses . $course_ID . ": " . Course::where('id', $course_ID)->first()->name . ", ";
        }
        
        $selected_course_ID = trim(explode(":", request('course_prime'))[0]);
        if($selected_course_ID != ''){
            $chapters = Course::where('id', $selected_course_ID)->first()->chapters;
            // {
            //     "chapter #1": {
            //         "lesson ID": "lesson Name",
            //         "lesson ID": "lesson Name",
            //         "lesson ID": "lesson Name",
            //     }
            //     "chapter #2": {
            //         "lesson ID": "lesson Name",
            //         "lesson ID": "lesson Name",
            //         "lesson ID": "lesson Name",
            //     }
            // }
            $chapters_data = "{ ";
            $chapters = json_decode($chapters);
            foreach ($chapters as $chapter) {
                $lessons = json_decode(Chapter::where('id', $chapter)->first()->lessons);
                $chapters_data = $chapters_data . "\"" . Chapter::where('id', $chapter)->first()->name . "\": {\n";
                foreach ($lessons as $lesson) {
                    $chapters_data = $chapters_data . "\"" . $lesson . 
                    "\": " . "\"" . Lesson::where('id', $lesson)->first()->name . "\",\n";
                }
                $chapters_data = substr_replace($chapters_data, "", -1);
                $chapters_data = substr_replace($chapters_data, "", -1);
                $chapters_data = $chapters_data . "},\n";
            }
            $chapters_data = substr_replace($chapters_data, "", -1);
            $chapters_data = substr_replace($chapters_data, "", -1);
            $chapters_data = $chapters_data . " }";
            return view('/main', ['major' => $major, 'courses' => $courses, 'chapters_data' => $chapters_data]);
        } else {
            return view('/main', ['major' => $major, 'courses' => $courses]);
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
