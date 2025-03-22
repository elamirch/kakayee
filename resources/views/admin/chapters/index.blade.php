<x-admin>
    <form action="/chapters/edit" method="get">
        @csrf
        <select name="chapter_id">
            @foreach ($chapters as $chapter)
                <option value="{{ $chapter->id }}">{{ $chapter->id }}: {{ $chapter->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
        
    <form action="/chapters/create" method="get">
        @csrf
        <br>
        <x-primary-button>Add a new chapter</x-primary-button>
    </form>
</x-admin>