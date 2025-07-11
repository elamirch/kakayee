<x-app-layout>
    <form action="/redirect_to_main" method="post">
        @csrf
        <x-primary-button>Back To Home</x-primary-button>
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