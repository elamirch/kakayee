<x-admin>
    <form action="/lessons/edit" method="get">
        @csrf
        <select name="lesson_id">
            @foreach ($lessons as $lesson)
                <option value="{{ $lesson->id }}">{{ $lesson->id }}: {{ $lesson->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
        
    <form action="/lessons/create" method="get">
        @csrf
        <br>
        <x-primary-button>Add a new lesson</x-primary-button>
    </form>
</x-admin>