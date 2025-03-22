<x-admin>
    <form action="/lessons" method="POST">
        @csrf
        <input type="text" name="lesson_name" placeholder="Lesson Name"><br>
        <br>
        <textarea name="lesson_notes" placeholder="Lesson Notes"></textarea>
        {{-- List of all cards --}}
        <br>
        <h2>Add Cards: </h2>
        <br>
        <select name="card_ids[]" multiple>
            @foreach ($cards as $card) 
                <option value="{{ $card->id }}">{{ $card->id }}: {{ $card->title }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Add Lesson</x-primary-button>
    </form>
</x-admin>