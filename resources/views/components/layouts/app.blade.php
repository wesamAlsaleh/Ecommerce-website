<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>

    {{-- to get the the preline components & tailwind csss --}}
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    {{-- to get live wire styles --}}
    @livewireStyles

</head>

<body class="bg-slate-200 dark:bg-slate-600">

    {{-- add the navbar --}}
    @livewire('partials.navbar')

    <main>
        {{ $slot }} {{-- i think this slot is the children to get the content of the components --}}
    </main>

    {{-- add the footer --}}
    @livewire('partials.footer')

    {{-- to get the vite script --}}
    @livewireScripts
</body>

</html>
