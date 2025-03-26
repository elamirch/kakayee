<x-admin>
    <form action="/courses" method="POST">
        @csrf
        <input type="text" name="course_name" placeholder="Course Name"><br>
        {{-- List of all lessons --}}
        <br>
        <h2>Add Lessons: </h2>
        <br>
        <select name="lesson_ids[]" multiple>
            @foreach ($lessons as $lesson) 
                <option value="{{ $lesson->id }}">{{ $lesson->id }}: {{ $lesson->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Add Course</x-primary-button>
    </form>
</x-admin>