<x-admin>
    <form action="/cards/{{$card->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="card_id" value="{{ $card->id }}">
        <input type="text" name="card_title" placeholder="Card Title" value="{{$card->title}}">
        <input type="text" name="card_content" placeholder="Card Content" value="{{$card->content}}">
        <input type="text" name="card_answer" placeholder="Card Answer" value="{{$card->answer}}">
        <br>
        <x-primary-button>Apply</x-primary-button>
    </form>
    <br><hr><br>
    <form action="/cards/{{$card->id}}" method="post">
        @csrf
        @method('DELETE')
        <x-primary-button>Delete Card</x-primary-button>
    </form>
</x-admin>