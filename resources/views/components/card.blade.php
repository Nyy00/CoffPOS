@props(['title' => '', 'subtitle' => '', 'padding' => true, 'shadow' => true])

{{-- Container utama card --}}
<div {{ $attributes->merge([
        'class' => 'bg-white rounded-lg border border-gray-200' . ($shadow ? ' shadow-sm' : '')
    ]) }}
>

    {{-- Header card (judul / subtitle / slot header) --}}
    @if($title || $subtitle || isset($header))
        <div class="px-6 py-4 border-b border-gray-200">

            {{-- Jika header dikirim sebagai slot --}}
            @isset($header)
                {{ $header }}

            {{-- Jika tidak ada slot header, gunakan title & subtitle --}}
            @else
                @if($title)
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $title }}
                    </h3>
                @endif

                @if($subtitle)
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $subtitle }}
                    </p>
                @endif
            @endisset
        </div>
    @endif

    {{-- Konten utama card --}}
    <div class="{{ $padding ? 'px-6 py-4' : '' }}">
        {{ $slot }}
    </div>

    {{-- Footer card (opsional) --}}
    @isset($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            {{ $footer }}
        </div>
    @endisset

</div>
