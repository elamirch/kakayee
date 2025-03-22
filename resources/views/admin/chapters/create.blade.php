<x-admin>
    <form action="/chapters" method="POST">
        @csrf
        <input type="text" name="chapter_name" placeholder="Chapter Name"><br>
        {{-- List of all chapters --}}
        <br>
        <h2>Add Lessons: </h2>
        <br>
        <select name="lesson_ids[]" multiple>
            @foreach ($lessons as $lesson) 
                <option value="{{ $lesson->id }}">{{ $lesson->id }}: {{ $lesson->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Add Chapter</x-primary-button>
    </form>
</x-admin>