<x-app-layout>
        <a>{{ $notes }}</a>
        <br>
        <form action="/lessons/{{$lesson_id}}" method="get">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ request('lesson_id') }}">
            <input type="hidden" name="step" value="1">
        <x-primary-button>رفتن به سوالات</x-primary-button>
    </form>
</x-app-layout>