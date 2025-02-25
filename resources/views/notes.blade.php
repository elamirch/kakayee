<x-app-layout>
    @isset($notes)
        <iframe style="width:100vw" src="{{ $notes }}" frameborder="0">
        </iframe>
        <br>
        <form action="{{ route('show') }}" method="post">
            @csrf
            <input type="hidden" name="lesson_ID" value="{{ request('lesson_ID') }}">
            <input type="hidden" name="step" value="1">
        <x-primary-button>Go to cards</x-primary-button>
        </form>
    @endisset
    @isset($step)
    @isset($check_answer)
    {{ $card_title }}<br>
    <form action="{{ route('show') }}" method="post">
        @csrf
        <input type="hidden" name="step" value="{{ $step + 1 }}">
        <input type="hidden" name="lesson_ID" value="{{ $lesson_ID }}">
        <br>
        @isset($right_answer)
        your answer: {{ $your_answer}}, right answer: {{ $right_answer }}
        @endisset
        <x-primary-button>Next</x-primary-button>
    </form>
    @else
        {{ $card_title }}<br>
        <form action="{{ route('show') }}" method="post">
            @csrf
            <input type="hidden" name="step" value="{{ $step + 1 }}">
            <input type="hidden" name="lesson_ID" value="{{ $lesson_ID }}">
            <input type="hidden" name="check_answer" value="true">
            <br>
            {!! $card_content !!}
            <br>
            <x-primary-button>Check Answer</x-primary-button>
        </form>
    @endisset
    @endisset
</x-app-layout>