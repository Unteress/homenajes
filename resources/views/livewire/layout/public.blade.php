<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jardín de los Recuerdos') }}</title>
        
        <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|playfair-display:400,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        {{ $slot }}
dghhghdfhghgh
        @livewireScripts
    </body>
</html>