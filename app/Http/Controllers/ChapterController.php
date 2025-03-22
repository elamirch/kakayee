<?php

namespace App\Http\Controllers;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Course;

use Illuminate\Http\Request;

class ChapterController extends Controller
{    
    public function index() {
        return view('admin.chapters.index', [
            'chapters' => Chapter::all()
        ]);
    }

    public function create() {
        return view('admin.chapters.create', ['lessons' => Lesson::all()]);
    }

    public function store() {
        $chapter = new Chapter;
        $chapter->name = request('chapter_name');
        $chapter->save();

        //Add new chapters
        if($lesson_ids = request('lesson_ids')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->chapter_id = $chapter->id;
                $lesson->save();
            }
        }

        return redirect("/chapters", 302);
    }

    public function redirect_to_edit(Request $request) {
        $selected_chapter_id = $request->chapter_id;
        return redirect("/chapters/$selected_chapter_id/edit", 302);
    }

    public function edit(Chapter $chapter) {
        return view('admin.chapters.edit', [
            'chapter' => $chapter->load('lessons'),
            'lessons' => Lesson::all()
        ]);
    }

    public function update(Chapter $chapter) {
        //Update chapter name
        $chapter->name = request('chapter_name');
        $chapter->save();

        //Add new lessons
        if($lesson_ids = request('lesson_ids')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->chapter_id = $chapter->id;
                $lesson->save();
            }
        }

        //Remove lessons
        if($lesson_ids = request('remove_lessons')) {
            foreach ($lesson_ids as $lesson_id) {
                $lesson = Lesson::find($lesson_id);
                $lesson->chapter_id = null;
                $lesson->save();
            }
        }

        return redirect("/chapters/$chapter->id/edit", 302);
    }

    public function destroy(Chapter $chapter) {
        $chapter->delete();
        return redirect("/", 302);
    }
}
