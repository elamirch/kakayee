@props(['title'])

<div class="bg-red-400 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
  @if(!empty($title))
      <strong class="font-bold">{{ $title }}</strong>
  @endif
  <span class="block sm:inline">{{ $slot }}</span>
</div>
