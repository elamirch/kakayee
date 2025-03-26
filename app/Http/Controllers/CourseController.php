<?php

namespace App\Http\Controllers;
use App\Models\Lesson;
use App\Models\Course;

use Illuminate\Http\Request;

class CourseController extends Controller
{    
    public function index() {
        return view('admin.courses.index', [
            'courses' => Course::all()
        ]);
    }

    public function create() {
        return view('admin.courses.create', ['lessons' => Lesson::all()]);
    }

    public function store() {
        $course = new Course;
        $course->name = request('course_name');
        $course->save();

        //Add new chapters
        if($lesson_ids = request('lesson_ids')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->course_id = $course->id;
                $lesson->save();
            }
        }

        return redirect("/courses", 302);
    }

    public function redirect_to_edit(Request $request) {
        $selected_course_id = $request->course_id;
        return redirect("/courses/$selected_course_id/edit", 302);
    }

    public function edit(Course $course) {
        return view('admin.courses.edit', [
            'course' => $course->load('lessons'),
            'lessons' => Lesson::all()
        ]);
    }

    public function update(Course $course) {
        //Update major name
        $course->name = request('course_name');
        $course->save();

        //Add new lessons
        if($lesson_ids = request('lesson_ids')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->course_id = $course->id;
                $lesson->save();
            }
        }

        //Remove courses
        if($lesson_ids = request('remove_lessons')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->course_id = null;
                $lesson->save();
            }
        }

        return redirect("/courses/$course->id/edit", 302);
    }

    public function destroy(Course $course) {
        $course->delete();
        return redirect("/", 302);
    }
}
