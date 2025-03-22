<x-admin>
    <form action="/lessons/{{$lesson->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
        <input type="text" name="lesson_name" value="{{ $lesson->name }}"><br><br>
        <textarea name="lesson_notes" placeholder="Lesson Notes">{{ $lesson->notes }}</textarea>
        <br>
        {{-- List of the currently selected lesson's cards --}}
        <h2>Remove Cards: </h2>
        <br>
        <ul>
            @foreach ( $lesson->cards as $card )
                <li>
                    <input type="checkbox" name="remove_cards[]" id="{{$card->id}}" value="{{$card->id}}">
                    <label for="{{$card->id}}">
                        {{ $card->id }}: {{ $card->title }}
                    </label>
                </li>
            @endforeach
        </ul>
        <br>
        {{-- List of all cards --}}
        <hr><br>
        <h2>Add Cards: </h2>
        <br>
        <select name="card_ids[]" multiple>
            @foreach ($cards as $card) 
                <option value="{{ $card->id }}">{{ $card->id }}: {{ $card->title }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Apply</x-primary-button>
    </form>
    <br><hr><br>
    <form action="/lessons/{{$lesson->id}}" method="post">
        @csrf
        @method('DELETE')
        <x-primary-button>Delete Lesson</x-primary-button>
    </form>
</x-admin>