<x-admin>
    <form action="/courses" method="POST">
        @csrf
        <input type="text" name="course_name" placeholder="Course Name"><br>
        {{-- List of all chapters --}}
        <br>
        <h2>Add Chapters: </h2>
        <br>
        <select name="chapter_ids[]" multiple>
            @foreach ($chapters as $chapter) 
                <option value="{{ $chapter->id }}">{{ $chapter->id }}: {{ $chapter->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Add Course</x-primary-button>
    </form>
</x-admin>