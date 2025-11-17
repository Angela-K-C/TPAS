<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-canvas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ $title ?? 'Temporary Pass Application System (TPAS)' }}</title>
    
    @vite('resources/css/app.css')
</head>
<body class="h-full font-sans antialiased">
    
    <div id="app" class="flex flex-col min-h-screen">
        <main class="flex-grow">
            @yield('content')
        </main>
        
        @if (($showFooter ?? true))
            <x-footer />
        @endif
        
    </div>

    @vite('resources/js/app.js')
</body>
</html>
