<?php

namespace App\Http\Controllers;
use App\Models\Major;
use App\Models\Course;

use Illuminate\Http\Request;

class MajorController extends Controller
{    
    public function index() {
        return view('admin.majors.index', [
            'majors' => Major::all()
        ]);
    }

    public function create() {
        return view('admin.majors.create', ['courses' => Course::all()]);
    }

    public function store() {
        $major = new Major;
        $major->name = request('major_name');
        $major->save();

        //Add new courses
        if($course_ids = request('course_ids')) {
            foreach ($course_ids as $course_id) {
                $course = Course::find($course_id);
                $course->major_id = $major->id;
                $course->save();
            }
        }

        return redirect("/majors", 302);
    }

    public function redirect_to_edit(Request $request) {
        $selected_major_id = $request->major_id;
        return redirect("/majors/$selected_major_id/edit", 302);
    }

    public function edit(Major $major) {
        return view('admin.majors.edit', [
            'major' => $major->load('courses'),
            'courses' => Course::all()
        ]);
    }

    public function update(Major $major) {
        //Update major name
        $major->name = request('major_name');
        $major->save();

        //Add new courses
        if($course_ids = request('course_ids')) {
            foreach ($course_ids as $course_id) {
                $course = Course::find($course_id);
                $course->major_id = $major->id;
                $course->save();
            }
        }

        //Remove courses
        if($course_ids = request('remove_courses')) {
            foreach ($course_ids as $course_id) {
                $course = Course::find($course_id);
                $course->major_id = null;
                $course->save();
            }
        }

        return redirect("/majors/$major->id/edit", 302);
    }

    public function destroy(Major $major) {
        $major->delete();
        return redirect("/", 302);
    }
}
