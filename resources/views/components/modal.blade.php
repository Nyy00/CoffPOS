@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
// Mapping ukuran lebar modal ke class Tailwind
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

{{-- Container utama modal --}}
<div
    {{-- Alpine.js state dan helper --}}
    x-data="{
        // Status tampil / tidak modal
        show: @js($show),

        // Mengambil semua elemen yang bisa difokuskan
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                .filter(el => ! el.hasAttribute('disabled'))
        },

        // Navigasi fokus keyboard
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { 
            return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) 
        },
        prevFocusableIndex() { 
            return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 
        },
    }"

    {{-- Watch perubahan show --}}
    x-init="$watch('show', value => {
        if (value) {
            // Disable scroll background saat modal terbuka
            document.body.classList.add('overflow-y-hidden');

            // Fokus ke elemen pertama jika focusable
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            // Aktifkan kembali scroll
            document.body.classList.remove('overflow-y-hidden');
        }
    })"

    {{-- Event global untuk membuka modal --}}
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"

    {{-- Event global untuk menutup modal --}}
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"

    {{-- Event close manual --}}
    x-on:close.stop="show = false"

    {{-- Tutup modal dengan tombol ESC --}}
    x-on:keydown.escape.window="show = false"

    {{-- Kontrol fokus dengan TAB --}}
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"

    {{-- Tampilkan modal --}}
    x-show="show"

    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
>

    {{-- Background overlay --}}
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false" {{-- Klik background menutup modal --}}
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    {{-- Konten modal --}}
    <div
        x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{-- Isi modal --}}
        {{ $slot }}
    </div>
</div>
