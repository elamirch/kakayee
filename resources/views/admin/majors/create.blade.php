<x-admin>
    <form action="/majors" method="POST">
        @csrf
        <input type="text" name="major_name" placeholder="Major Name"><br>
        {{-- List of all courses --}}
        <br>
        <h2>Add Courses: </h2>
        <br>
        <select name="course_ids[]" multiple>
            @foreach ($courses as $course) 
                <option value="{{ $course->id }}">{{ $course->id }}: {{ $course->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Add Major</x-primary-button>
    </form>
</x-admin>