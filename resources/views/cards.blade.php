<x-app-layout>
    <form action="/lessons/{{$lesson_id}}/check" method="post">
        @csrf
        <br>
        {!! $card->content !!}
        <br>
        <i>تاریخ: {{ $card->date }}</i>
        <br>
        <x-primary-button>بعدی</x-primary-button>
    </form>
        @if(session('answer_status') && isset(session('answer_status')['flash_message']))
            @php
                if(session('answer_status')['right']) {
                    $title = "پاسخ شما درست بود";
                } else {
                    $title = "پاسخ شما نادرست بود";
                }
            @endphp
            <x-flash-message title="{{$title}}">
                {!! session('answer_status')['flash_message'] !!}
            </x-flash-message>
        @endif
</x-app-layout>