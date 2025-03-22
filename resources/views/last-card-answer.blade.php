<x-app-layout>
    <form action="/redirect_to_main" method="post">
        @csrf
        <x-primary-button>Back To Home</x-primary-button>
    </form>
    <x-red-flash-message>
        {{ session('answer_status') }}
    </x-red-flash-message>
</x-app-layout>