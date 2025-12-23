@props(['id' => 'modal', 'title' => '', 'size' => 'md', 'type' => 'default'])

@php
// Mapping ukuran modal ke class Tailwind
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-full mx-4',
];

// Mapping tipe modal ke tampilan
$typeClasses = [
    'default' => 'bg-white',
    'confirmation' => 'bg-white',
    'danger' => 'bg-white border-t-4 border-red-500',
];
@endphp

{{-- Wrapper modal (default tersembunyi) --}}
<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-y-auto hidden"
    aria-labelledby="{{ $id }}-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        {{-- Background overlay --}}
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            aria-hidden="true"
            onclick="closeModal('{{ $id }}')"
        ></div>

        {{-- Trick untuk vertical center di layar besar --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Panel modal --}}
        <div
            class="inline-block align-bottom {{ $typeClasses[$type] }} rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $sizeClasses[$size] }} sm:w-full"
        >

            {{-- Header modal (jika title ada) --}}
            @if($title)
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                <div class="flex items-start">

                    {{-- Ikon khusus untuk confirmation / danger --}}
                    @if($type === 'confirmation' || $type === 'danger')
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full
                        {{ $type === 'danger' ? 'bg-red-100' : 'bg-blue-100' }}
                        sm:mx-0 sm:h-10 sm:w-10"
                    >

                        {{-- Ikon danger --}}
                        @if($type === 'danger')
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>

                        {{-- Ikon confirmation --}}
                        @else
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endif
                    </div>
                    @endif

                    {{-- Judul modal --}}
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="{{ $id }}-title">
                            {{ $title }}
                        </h3>

                        {{-- Tombol close --}}
                        <button
                            type="button"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                            onclick="closeModal('{{ $id }}')"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Body modal --}}
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                {{ $slot }}
            </div>

            {{-- Footer modal (opsional) --}}
            @isset($footer)
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                {{ $footer }}
            </div>
            @endisset

        </div>
    </div>
</div>

{{-- Script kontrol modal --}}
<script>
/**
 * Membuka modal berdasarkan ID
 */
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

/**
 * Menutup modal berdasarkan ID
 */
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

/**
 * Menutup semua modal saat tombol ESC ditekan
 */
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});
</script>
