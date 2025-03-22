<x-app-layout>
    {{ $card->title }}<br>
    <form action="/lessons/{{$lesson_id}}/check" method="post">
        @csrf
        <br>
        {!! $card->content !!}
        <x-primary-button>Next</x-primary-button>
    </form>
    <x-red-flash-message>
        {{ session('answer_status') }}
    </x-red-flash-message>
</x-app-layout>