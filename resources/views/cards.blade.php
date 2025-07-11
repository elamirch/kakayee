<x-app-layout>
    <form action="/lessons/{{$lesson_id}}/check" method="post">
        @csrf
        <br>
        {!! $card->content !!}
        <br>
        Date: {{ $card->date }}
        <br>
        <x-primary-button>Next</x-primary-button>
    </form>
        @if(session('answer_status') && isset(session('answer_status')['flash_message']))
            @php
                if(session('answer_status')['right']) {
                    $title = "Right";
                } else {
                    $title = "Wrong";
                }
            @endphp
            <x-flash-message title="{{$title}}">
                {!! session('answer_status')['flash_message'] !!}
            </x-flash-message>
        @endif
</x-app-layout>