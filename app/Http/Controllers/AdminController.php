<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Major;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function cards() {
        if(request('card_content') != null) {
            $card = Card::find(request('card_id'));
            $card->content = request('card_content');
            $card->title = request('card_title');
            $card->save();
        }
        
        if(request('new_card') != null){
            $card = new Card;
            $card->title = fake()->text(10);
            $card->content = fake()->text();
            $card->answer = fake()->text(10);
            $card->save();
        }

        if(request('delete_card') !=null) {
            Card::where('id', request('id_to_delete'))->delete();
        }

        $list = Card::all()->reverse()->pluck(null, 'id');
        return view('admin', ['card' => true, 'list' => $list, 'card_id' => request('card_id')]);
    }
    
    public function lessons() {

        if(request('lesson_notes') != null) {
            $lesson = Lesson::find(request('lesson_id'));
            $lesson->notes = request('lesson_notes');
            $lesson->name = request('lesson_name');
            if(request('card_ids') != null) {
                $lesson->cards = json_encode(array_merge(json_decode($lesson->cards), request('card_ids')));
            }
            $lesson->save();
        }

        if(request('new_lesson') != null){
            $lesson = new Lesson;
            $lesson->name = 'Lesson ' . fake()->text(10);
            $lesson->cards = '[]';
            $lesson->notes = fake()->url();
            $lesson->save();
        }

        if(request('delete_lesson') !=null) {
            Lesson::where('id', request('id_to_delete'))->delete();
        }

        if(request('remove_card')){
            $lesson = Lesson::find(request('lesson_id'));
            $lesson->cards = json_encode(array_values(array_diff(json_decode($lesson->cards), [request('remove_card')])));
            $lesson->save();
        }

        $list = Lesson::all()->reverse()->pluck(null, 'id');
        $cards = Card::all()->reverse()->pluck(null, 'id');
        return view('admin', ['lesson' => true, 'list' => $list, 'lesson_id' => request('lesson_id'), 'cards' => $cards]);
    }
    
    public function chapters() {


        if(request('new_chapter') != null){
            $chapter = new Chapter;
            $chapter->name = 'Chapter ' . fake()->text(10);
            $chapter->lessons = '[]';
            $chapter->save();
        }
        
        if(request('chapter_name') != null) {
            $chapter = Chapter::find(request('chapter_id'));
            $chapter->name = request('chapter_name');
            if(request('lesson_ids') != null) {
                $chapter->lessons = json_encode(array_merge(json_decode($chapter->lessons), request('lesson_ids')));
            }
            $chapter->save();
        }

        if(request('delete_chapter') !=null) {
            Chapter::where('id', request('id_to_delete'))->delete();
        }
        
        if(request('remove_lesson')){
            $chapter = Chapter::find(request('chapter_id'));
            $chapter->lessons = json_encode(array_values(array_diff(json_decode($chapter->lessons), [request('remove_lesson')])));
            $chapter->save();
        }

        $list = Chapter::all()->reverse()->pluck(null, 'id');
        $lessons = Lesson::all()->reverse()->pluck(null, 'id');
        return view('admin', ['chapter' => true, 'list' => $list, 'chapter_id' => request('chapter_id'), 'lessons' => $lessons]);
    }
    
    public function courses() {
        if(request('new_course') != null){
            $course = new Course;
            $course->name = 'Course ' . fake()->text(10);
            $course->chapters = '[]';
            $course->save();
        }
        
        if(request('course_name') != null) {
            $course = Course::find(request('course_id'));
            $course->name = request('course_name');
            if(request('chapter_ids') != null) {
                $course->chapters = json_encode(array_merge(json_decode($course->chapters), request('chapter_ids')));
            }
            $course->save();
        }

        if(request('delete_course') !=null) {
            Course::where('id', request('id_to_delete'))->delete();
        }
        
        if(request('remove_chapter')){
            $course = Course::find(request('course_id'));
            $course->chapters = json_encode(array_values(array_diff(json_decode($course->chapters), [request('remove_chapter')])));
            $course->save();
        }

        $list = Course::all()->reverse()->pluck(null, 'id');
        $chapters = Chapter::all()->reverse()->pluck(null, 'id');
        return view('admin', ['course' => true, 'list' => $list, 'course_id' => request('course_id'), 'chapters' => $chapters]);
    

    }
    
    public function majors() {
        if(request('new_major') != null){
            $major = new Major;
            $major->name = 'Major ' . fake()->text(10);
            $major->courses = '[]';
            $major->save();
        }
        
        if(request('major_name') != null) {
            $major = Major::find(request('major_id'));
            $major->name = request('major_name');
            if(request('course_ids') != null) {
                $major->courses = json_encode(array_merge(json_decode($major->courses), request('course_ids')));
            }
            $major->save();
        }

        if(request('delete_major') !=null) {
            Major::where('id', request('id_to_delete'))->delete();
        }
        
        if(request('remove_course')){
            $major = Major::find(request('major_id'));
            $major->courses = json_encode(array_values(array_diff(json_decode($major->courses), [request('remove_course')])));
            $major->save();
        }

        $list = Major::all()->reverse()->pluck(null, 'id');
        $courses = Course::all()->reverse()->pluck(null, 'id');
        return view('admin', ['major' => true, 'list' => $list, 'major_id' => request('major_id'), 'courses' => $courses]);
    

    }
    
    
}
