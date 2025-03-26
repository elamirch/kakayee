<x-app-layout>
    <form action="/" method="get">
        @csrf
        @php
            $selected_course_id = request('selected_course_id') ?? null;
        @endphp
        <select name="selected_course_id" id="selected_course_id">
            @if(!is_null($selected_course_id))
                <option value="{{$selected_course_id}}" selected hidden>{{ $major->courses[$selected_course_id-1]->name }}</option>
            @endif
            @foreach ($major->courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
        {{ session('lesson_completed') }}
    @isset($selected_course)
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach ( $selected_course->chapters as $chapter)
                        {{ $chapter->name }}<hr>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        @foreach ( $chapter->lessons as $lesson)
                            <form action="/lessons/{{ $lesson->id }}" method="get">
                                @csrf
                                <x-primary-button style="padding: 25px; margin: 10px;">{{$lesson->name}}</x-primary-button>
                            </form>
                        @endforeach
                        </div>
                        <br><br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endisset
    
</x-app-layout>