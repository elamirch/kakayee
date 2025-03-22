<x-admin>
    <form action="/courses/edit" method="get">
        @csrf
        <select name="course_id">
            @foreach ($courses as $course)
                <option value="{{ $course->id }}">{{ $course->id }}: {{ $course->name }}</option>
            @endforeach
        </select>
        <x-primary-button>Select</x-primary-button>
    </form>
        
    <form action="/courses/create" method="get">
        @csrf
        <br>
        <x-primary-button>Add a new course</x-primary-button>
    </form>
</x-admin>