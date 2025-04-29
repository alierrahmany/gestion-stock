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

    @if($canEdit)
        <button onclick="document.getElementById('profileImageForm{{ $userId }}').click()"
                class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-lg">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
        </button>
        <form action="{{ route('users.update-image', $userId) }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            @method('PUT')
            <input type="file" id="profileImageForm{{ $userId }}" name="profile_image"
                   onchange="this.form.submit()" accept="image/*">
        </form>
    @endif
</div>
