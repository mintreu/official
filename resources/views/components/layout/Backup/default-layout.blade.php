<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')
    @stack('style')
    @livewireStyles
</head>
<body class="antialiased scrollbar-hide">
{{ $slot }}
@livewireScripts

@stack('javascript')
@stack('script')
</body>
</html>
