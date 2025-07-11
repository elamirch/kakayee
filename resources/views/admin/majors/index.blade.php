<x-admin>
    <form action="/majors/edit" method="get">
        @csrf
        <select name="major_id">
            @foreach ($majors as $major)
                <option value="{{ $major->id }}">{{ $major->id }}: {{ $major->name }}</option>
            @endforeach
        </select>
        <x-primary-button>انتخاب</x-primary-button>
    </form>
        
    <form action="/majors/create" method="get">
        @csrf
        <br>
        <x-primary-button>افزودن رشته جدید</x-primary-button>
    </form>
</x-admin>