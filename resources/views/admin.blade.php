<x-app-layout>
    <style>
        <style>
    .menu-xerac {
        list-style-type: none;
        padding: 0;
    }
    
    .menu-item-xerac {
        margin-top: 10px;
        display: inline-block;
    }
    
    .menu-link-xerac {
        padding: 10px;
        color: white;
        text-decoration: none;
    }
    
    .menu-link-xerac:hover {
        background-color: white;
        color: rgb(17, 24, 39);
    }
    </style>
    <ul class="menu-xerac">
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/admin/cards">Cards</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/admin/lessons">Lessons</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/admin/chapters">Chapters</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/admin/courses">Courses</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/admin/majors">Majors</a></li>
    </ul>
    <br>

    <!-- Cards tab -->
    @isset($card)
        <form action="/admin/cards" method="post">
        @csrf
                <select name="card_id">
                    @if($card_id != null)
                        <option value="{{$card_id}}" selected hidden>{{ $list[$card_id]->id }}: {{ $list[$card_id]->title }}</option>
                    @endif
                    @foreach ($list as $item)
                        <option value="{{ $item->id }}">{{ $item->id }}: {{ $item->title }}</option>
                    @endforeach
                </select>
            <x-primary-button>Select</x-primary-button>
        </form>
        <form action="/admin/cards" method="post">
        @csrf
        <input type="hidden" name="new_card" value="true">
        <x-primary-button>Add a new card</x-primary-button>
        </form>
        @if($card_id != null)
        <br><hr><br>
            <form action="/admin/cards" method="post">
            @csrf
                <input type="hidden" name="card_id" value="{{ $card_id }}">
                <input type="text" name="card_title" value="{{ $list[$card_id]->title }}"><br>
                <textarea name="card_content" cols="30" rows="10">{{ $list[$card_id]->content }}</textarea>           
                <br>
                <x-primary-button>Apply</x-primary-button>
            </form>
            <form action="/admin/cards" method="post">
                @csrf
                    <input type="hidden" name="delete_card" value="true">
                    <input type="hidden" name="id_to_delete" value="{{ $card_id }}">
                    <x-primary-button>Delete Card</x-primary-button>
                </form>
        @endif
    @endisset

    <!-- Lessons tab -->
    @isset($lesson)
        <form action="/admin/lessons" method="post">
        @csrf
                <select name="lesson_id">
                    @if($lesson_id != null)
                        <option value="{{$lesson_id}}" selected hidden>{{ $list[$lesson_id]->id }}: {{ $list[$lesson_id]->name }}</option>
                    @endif
                    @foreach ($list as $item)
                        <option value="{{ $item->id }}">{{ $item->id }}: {{ $item->name }}</option>
                    @endforeach
                </select>
            <x-primary-button>Select</x-primary-button>
        </form>
        
        <form action="/admin/lessons" method="post">
        @csrf
        <input type="hidden" name="new_lesson" value="true">
        <x-primary-button>Add a new lesson</x-primary-button>
        </form>

        @if($lesson_id != null)
        <br><hr><br>
            <form action="/admin/lessons" method="post">
            @csrf
                <input type="hidden" name="lesson_id" value="{{ $lesson_id }}">
                <input type="text" name="lesson_name" value="{{ $list[$lesson_id]->name }}"><br>
                <input type="text" name="lesson_notes" value="{{ $list[$lesson_id]->notes }}">
                <br>
                <select name="card_ids[]" multiple>
                    @foreach ($cards as $card)
                        <option value="{{ $card->id }}">{{ $card->id }}: {{ $card->title }}</option>
                    @endforeach
                </select>
                <x-primary-button>Apply</x-primary-button>
            </form>
            <form action="/admin/lessons" method="post">
            @csrf
                <input type="hidden" name="delete_lesson" value="true">
                <input type="hidden" name="id_to_delete" value="{{ $lesson_id }}">
                <x-primary-button>Delete Lesson</x-primary-button>
            </form>
            <ul class="menu-xerac">
                @foreach (json_decode($list[$lesson_id]->cards) as $card)
                    <form action="/admin/lessons" method="post">
                    @csrf
                        <input type="hidden" name="lesson_id" value="{{$lesson_id}}">
                        <input type="hidden" name="remove_card" value="{{$card}}">
                        <li class="menu-item-xerac"><a class="menu-link-xerac">{{ $card }}</a><x-primary-button>Remove card</x-primary-button></li>
                    </form>
                @endforeach           
                </ul>
        @endif
    @endisset

    <!-- Chapters tab -->
    @isset($chapter)
    <form action="/admin/chapters" method="post">
        @csrf
                <select name="chapter_id">
                    @if($chapter_id != null)
                        <option value="{{$chapter_id}}" selected hidden>{{ $list[$chapter_id]->id }}: {{ $list[$chapter_id]->name }}</option>
                    @endif
                    @foreach ($list as $item)
                        <option value="{{ $item->id }}">{{ $item->id }}: {{ $item->name }}</option>
                    @endforeach
                </select>
            <x-primary-button>Select</x-primary-button>
        </form>
        
        <form action="/admin/chapters" method="post">
        @csrf
        <input type="hidden" name="new_chapter" value="true">
        <x-primary-button>Add a new chapter</x-primary-button>
        </form>


        @if($chapter_id != null)
        <br><hr><br>
            <form action="/admin/chapters" method="post">
            @csrf
                <input type="hidden" name="chapter_id" value="{{ $chapter_id }}">
                <input type="text" name="chapter_name" value="{{ $list[$chapter_id]->name }}"><br>
                <br>
                <select name="lesson_ids[]" multiple>
                    @foreach ($lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->id }}: {{ $lesson->name }}</option>
                    @endforeach
                </select>
                <x-primary-button>Apply</x-primary-button>
            </form>
            <form action="/admin/chapters" method="post">
            @csrf
                <input type="hidden" name="delete_chapter" value="true">
                <input type="hidden" name="id_to_delete" value="{{ $chapter_id }}">
                <x-primary-button>Delete Chapter</x-primary-button>
            </form>
            <ul class="menu-xerac">
                @foreach (json_decode($list[$chapter_id]->lessons) as $lesson)
                    <form action="/admin/chapters" method="post">
                    @csrf
                        <input type="hidden" name="chapter_id" value="{{$chapter_id}}">
                        <input type="hidden" name="remove_lesson" value="{{$lesson}}">
                        <li class="menu-item-xerac"><a class="menu-link-xerac">{{ $lesson }}</a><x-primary-button>Remove lesson</x-primary-button></li>
                    </form>
                @endforeach           
                </ul>
        @endif
    @endisset


    <!-- Courses tab -->
    @isset($course)
    <form action="/admin/courses" method="post">
        @csrf
                <select name="course_id">
                    @if($course_id != null)
                        <option value="{{$course_id}}" selected hidden>{{ $list[$course_id]->id }}: {{ $list[$course_id]->name }}</option>
                    @endif
                    @foreach ($list as $item)
                        <option value="{{ $item->id }}">{{ $item->id }}: {{ $item->name }}</option>
                    @endforeach
                </select>
            <x-primary-button>Select</x-primary-button>
        </form>
        
        <form action="/admin/courses" method="post">
        @csrf
        <input type="hidden" name="new_course" value="true">
        <x-primary-button>Add a new course</x-primary-button>
        </form>


        @if($course_id != null)
        <br><hr><br>
            <form action="/admin/courses" method="post">
            @csrf
                <input type="hidden" name="course_id" value="{{ $course_id }}">
                <input type="text" name="course_name" value="{{ $list[$course_id]->name }}"><br>
                <br>
                <select name="chapter_ids[]" multiple>
                    @foreach ($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->id }}: {{ $chapter->name }}</option>
                    @endforeach
                </select>
                <x-primary-button>Apply</x-primary-button>
            </form>
            <form action="/admin/courses" method="post">
            @csrf
                <input type="hidden" name="delete_course" value="true">
                <input type="hidden" name="id_to_delete" value="{{ $course_id }}">
                <x-primary-button>Delete Course</x-primary-button>
            </form>
            <ul class="menu-xerac">
                @foreach (json_decode($list[$course_id]->chapters) as $chapter)
                    <form action="/admin/courses" method="post">
                    @csrf
                        <input type="hidden" name="course_id" value="{{$course_id}}">
                        <input type="hidden" name="remove_chapter" value="{{$chapter}}">
                        <li class="menu-item-xerac"><a class="menu-link-xerac">{{ $chapter }}</a><x-primary-button>Remove chapter</x-primary-button></li>
                    </form>
                @endforeach           
                </ul>
        @endif
    @endisset


<!-- Majors tab -->
@isset($major)
<form action="/admin/majors" method="post">
    @csrf
            <select name="major_id">
                @if($major_id != null)
                    <option value="{{$major_id}}" selected hidden>{{ $list[$major_id]->id }}: {{ $list[$major_id]->name }}</option>
                @endif
                @foreach ($list as $item)
                    <option value="{{ $item->id }}">{{ $item->id }}: {{ $item->name }}</option>
                @endforeach
            </select>
        <x-primary-button>Select</x-primary-button>
    </form>
    
    <form action="/admin/majors" method="post">
    @csrf
    <input type="hidden" name="new_major" value="true">
    <x-primary-button>Add a new major</x-primary-button>
    </form>


    @if($major_id != null)
    <br><hr><br>
        <form action="/admin/majors" method="post">
        @csrf
            <input type="hidden" name="major_id" value="{{ $major_id }}">
            <input type="text" name="major_name" value="{{ $list[$major_id]->name }}"><br>
            <br>
            <select name="course_ids[]" multiple>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->id }}: {{ $course->name }}</option>
                @endforeach
            </select>
            <x-primary-button>Apply</x-primary-button>
        </form>
        <form action="/admin/majors" method="post">
        @csrf
            <input type="hidden" name="delete_major" value="true">
            <input type="hidden" name="id_to_delete" value="{{ $major_id }}">
            <x-primary-button>Delete Major</x-primary-button>
        </form>
        <ul class="menu-xerac">
            @foreach (json_decode($list[$major_id]->courses) as $course)
                <form action="/admin/majors" method="post">
                @csrf
                    <input type="hidden" name="major_id" value="{{$major_id}}">
                    <input type="hidden" name="remove_course" value="{{$course}}">
                    <li class="menu-item-xerac"><a class="menu-link-xerac">{{ $course }}</a><x-primary-button>Remove course</x-primary-button></li>
                </form>
            @endforeach           
            </ul>
    @endif
@endisset
</x-app-layout>