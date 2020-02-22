<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.partials.meta')

    <title>@yield('page.title', 'PHP & Laravel development') - ollieread.com</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @stack('page.header')
</head>
<body class="layout layout--main">

<div class="container" id="app">

    @yield('header')

    @yield('content')

</div>

<script src="{{ mix('js/app.js') }}"></script>
@stack('page.footer')

</body>
</html>
