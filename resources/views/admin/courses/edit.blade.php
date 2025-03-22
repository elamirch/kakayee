<x-admin>
    <form action="/courses/{{$course->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="text" name="course_name" value="{{ $course->name }}"><br>
        <br>
        {{-- List of the currently selected course's chapters --}}
        <h2>Remove Chapters: </h2>
        <br>
        <ul>
            @foreach ( $course->chapters as $chapter )
                <li>
                    <input type="checkbox" name="remove_chapters[]" id="{{$chapter->id}}" value="{{$chapter->id}}">
                    <label for="{{$chapter->id}}">
                        {{ $chapter->id }}: {{ $chapter->name }}
                    </label>
                </li>
            @endforeach
        </ul>
        <br>
        {{-- List of all chapters --}}
        <hr><br>
        <h2>Add Chapter: </h2>
        <br>
        <select name="chapter_ids[]" multiple>
            @foreach ($chapters as $chapter) 
                <option value="{{ $chapter->id }}">{{ $chapter->id }}: {{ $chapter->name }}</option>
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