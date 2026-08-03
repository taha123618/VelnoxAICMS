<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'VelnoxAI CMS') }}</title>
        
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        @routes
        @inertiaHead
        {{-- @vite(['resources/js/app.ts']) --}}
        
        @if(count(explode('::',$page['component'])) > 1)
            @php
                $module = explode('::',$page['component'])[0];
                $path = explode('::',$page['component'])[1];
            @endphp
            @vite(['resources/css/app.css','resources/js/app.ts', "Modules/{$module}/resources/views/{$path}.vue"])
        @else
            @vite(['resources/css/app.css','resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @endif
        
        
    </head>
    <body class="font-sans antialiased h-full">
        @inertia
    </body>
</html>
