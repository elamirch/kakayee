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

        $major_id = Auth::user()->major_id;
        $selected_course_id = request('selected_course_id');

        if(isset($selected_course_id)){
            return view('/main', [
                'major' => Major::with('courses')->find($major_id),
                'selected_course' => Course::with(['chapters.lessons'])->find($selected_course_id)
            ]);
        } else {
            return view('/main', ['major' => Major::with('courses')->find($major_id)]);
        }
    }

}
