<x-admin>
    <form action="/courses/{{$course->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="text" name="course_name" value="{{ $course->name }}"><br>
        <br>
        {{-- List of the currently selected course's lessons --}}
        <h2>Remove Lessons: </h2>
        <br>
        <ul>
            @foreach ( $course->lessons as $lesson )
                <li>
                    <input type="checkbox" name="remove_lessons[]" id="{{$lesson->id}}" value="{{$lesson->id}}">
                    <label for="{{$lesson->id}}">
                        {{ $lesson->id }}: {{ $lesson->name }}
                    </label>
                </li>
            @endforeach
        </ul>
        <br>
        {{-- List of all lessons --}}
        <hr><br>
        <h2>Add Lesson: </h2>
        <br>
        <select name="lesson_ids[]" multiple>
            @foreach ($lessons as $lesson) 
                <option value="{{ $lesson->id }}">{{ $lesson->id }}: {{ $lesson->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Apply</x-primary-button>
    </form>
    <br><hr><br>
    <form action="/courses/{{$course->id}}" method="post">
        @csrf
        @method('DELETE')
        <x-primary-button>Delete Course</x-primary-button>
    </form>
</x-admin>