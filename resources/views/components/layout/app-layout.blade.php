<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}


    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }

        .header-text {
            text-shadow: 2.5px 2.5px 2px #d946ef;
        }
    </style>

    <script>
        const theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

        if (
            theme === 'dark' ||
            (theme === 'system' &&
                window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)
        ) {
            document.documentElement.classList.add('dark')
        }
    </script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    @filamentStyles

{{--    @vite(['resources/css/filament/app/theme.css'])--}}
    @vite(['resources/css/app.css'])
    @stack('style')
    {{--    @vite(['resources/css/app.css'])--}}
</head>

<body class="antialiased">
{{ $slot }}

@filamentScripts
@vite('resources/js/app.js')
@stack('javascript')
@stack('script')
</body>
</html>
