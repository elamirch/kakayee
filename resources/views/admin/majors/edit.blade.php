<x-admin>
    <form action="/majors/{{$major->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="major_id" value="{{ $major->id }}">
        <input type="text" name="major_name" value="{{ $major->name }}"><br>
        <br>
        {{-- List of the currently selected majors courses --}}
        <h2>Remove Courses: </h2>
        <br>
        <ul>
            @foreach ( $major->courses as $course )
                <li>
                    <input type="checkbox" name="remove_courses[]" id="{{$course->id}}" value="{{$course->id}}">
                    <label for="{{$course->id}}">
                        {{ $course->id }}: {{ $course->name }}
                    </label>
                </li>
            @endforeach
        </ul>
        <br>
        {{-- List of all courses --}}
        <hr><br>
        <h2>Add Courses: </h2>
        <br>
        <select name="course_ids[]" multiple>
            @foreach ($courses as $course) 
                <option value="{{ $course->id }}">{{ $course->id }}: {{ $course->name }}</option>
            @endforeach
        </select>
        <br><br><hr><br>
        <x-primary-button>Apply</x-primary-button>
    </form>
    <br><hr><br>
    <form action="/majors/{{$major->id}}" method="post">
        @csrf
        @method('DELETE')
        <x-primary-button>Delete Major</x-primary-button>
    </form>
</x-admin>