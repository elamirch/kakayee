<x-app-layout>
    <form action="{{ route('main') }}" method="get">
        @csrf
        {{-- course_prime was selected as course was not available --}}
        @php
            $course_prime = request('course_prime');
        @endphp
        <select name="course_prime" id="course_prime">
                @if($course_prime != '')
                <option value="{{$course_prime}}" selected hidden>{{ trim(explode(":" , $course_prime)[1]) }}</option>
                @endif
                @foreach (explode(',' , substr_replace($courses, "", -2)) as $course)
                    @php
                        $course_name = explode(':', $course)[1];
                    @endphp
                    <option value="{{ trim($course) }}">{{ trim($course_name) }}</option>
                @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
    @isset($chapters_data)
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @foreach ( json_decode($chapters_data) as $chapter_name=>$lessons)
                        {{ $chapter_name }}<hr>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        @foreach ( $lessons as $lesson_ID=>$lesson_name)
                        <form action="{{ route('show') }}" method="post">
                            @csrf
                            <input type="hidden" name="lesson_ID" value="{{ $lesson_ID }}"/>
                            <x-primary-button style="padding: 25px; margin: 10px;">{{$lesson_name}}</x-primary-button>
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