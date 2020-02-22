<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.partials.meta')

    <title>@yield('Title', '')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @stack('page.header')
</head>
<body class="layout layout--single">

<div class="container">

    <main class="page">

        <header class="page__header">
            <div class="logo"></div>

            <div>
                <h2 class="page__header-heading">Ollie Read</h2>
                <h3 class="page__header-subheading">PHP & Laravel Development</h3>
            </div>
        </header>

        @yield('content')

    </main>

</div>

<script src="{{ mix('js/app.js') }}"></script>
@stack('page.footer')
</body>
</html>
