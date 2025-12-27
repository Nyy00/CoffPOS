<img src="{{ asset('images/logo-fallback.png') }}" 
     {{ $attributes->merge(['alt' => config('app.name', 'CoffPOS'), 'class' => 'h-8 w-auto']) }} 
     onerror="this.onerror=null; this.src='{{ asset('storage/logo.png') }}'; this.alt='CoffPOS';" />
