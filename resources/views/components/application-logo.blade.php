<img src="{{ asset('storage/logo.png') }}" 
     {{ $attributes->merge(['alt' => config('app.name', 'CoffPOS'), 'class' => 'h-8 w-auto']) }} 
     onerror="this.onerror=null; this.src='{{ asset('images/logo-fallback.png') }}'; this.alt='CoffPOS';" />
