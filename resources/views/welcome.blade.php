<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>{{ config('app.name', 'INFAQABIM') }}</title>

        <!-- Dynamic Open Graph / SEO tags -->
        <meta property="og:title" content="{{ $og['title'] ?? config('app.name', 'INFAQABIM') }}" />
        <meta property="og:description" content="{{ $og['description'] ?? 'Sumbangan dan Dana' }}" />
        <meta property="og:type" content="{{ $og['type'] ?? 'website' }}" />
        <meta property="og:url" content="{{ request()->fullUrl() }}" />
        @if(isset($og['image']))
            <meta property="og:image" content="{{ $og['image'] }}" />
        @endif
        
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
