<x-admin>
    <form action="/cards/edit" method="get">
        @csrf
        <select name="card_id">
            @foreach ($cards as $card)
                <option value="{{ $card->id }}">{{ $card->id }}: {{ $card->title }}</option>
            @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
        
    <form action="/cards/create" method="get">
        @csrf
        <br>
        <x-primary-button>Add a new card</x-primary-button>
    </form>
</x-admin>