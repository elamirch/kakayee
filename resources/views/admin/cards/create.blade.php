<x-admin>
    <form action="/cards" method="POST">
        @csrf
        <input type="text" name="card_title" placeholder="Card Title">
        <input type="text" name="card_content" placeholder="Card Content">
        <input type="text" name="card_answer" placeholder="Card Answer">
        <x-primary-button>Add Card</x-primary-button>
    </form>
</x-admin>