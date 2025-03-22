<?php

namespace App\Http\Controllers;
use App\Models\Chapter;
use App\Models\Major;
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
        return view('admin.courses.create', ['chapters' => Chapter::all()]);
    }

    public function store() {
        $course = new Course;
        $course->name = request('course_name');
        $course->save();

        //Add new chapters
        if($chapter_ids = request('chapter_ids')) {
            foreach ($chapter_ids as $chapter_id) {
                $chapter = Chapter::find($chapter_id);
                $chapter->course_id = $course->id;
                $chapter->save();
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
            'course' => $course->load('chapters'),
            'chapters' => Chapter::all()
        ]);
    }

    public function update(Course $course) {
        //Update major name
        $course->name = request('course_name');
        $course->save();

        //Add new chapters
        if($chapter_ids = request('chapter_ids')) {
            foreach ($chapter_ids as $chapter_id) {
                $chapter = Chapter::find($chapter_id);
                $chapter->course_id = $course->id;
                $chapter->save();
            }
        }

        //Remove courses
        if($chapter_ids = request('remove_chapters')) {
            foreach ($chapter_ids as $chapter_id) {
                $chapter = Chapter::find($chapter_id);
                $chapter->course_id = null;
                $chapter->save();
            }
        }

        return redirect("/courses/$course->id/edit", 302);
    }

    public function destroy(Course $course) {
        $course->delete();
        return redirect("/", 302);
    }
}
