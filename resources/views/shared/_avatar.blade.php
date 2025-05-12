@php
    $size = $size ?? '10';
    $cssClass = $class ?? '';
    $fontSize = intval($size) > 16 ? 'text-3xl' : 'text-sm';
    $userId = $userId ?? null;
    $canEdit = $userId && (auth()->user()->isAdmin() || auth()->id() === $userId);  // Allow admin or self edit
@endphp

<div class="relative">
    @if($image && file_exists(public_path('storage/profile_images/'.$image)))
        <img class="h-{{ $size }} w-{{ $size }} rounded-full object-cover {{ $cssClass }}"
            src="{{ asset('storage/profile_images/'.$image) }}"
            alt="{{ $name }}">
    @else
        <div class="h-{{ $size }} w-{{ $size }} rounded-full bg-blue-600 flex items-center justify-center text-white font-bold {{ $fontSize }} {{ $cssClass }}">
            {{ strtoupper(substr($name, 0, 1)) }}
        </div>
    @endif

</div>
