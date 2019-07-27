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
<body class="layout layout--main">

<div class="container" id="app">

    <header class="brand">
        <span class="brand__menu-link" id="sidebar__open">
            <i class="fa fa-fw fa-bars"></i>
        </span>
        <div class="logo"></div>

        <h2 class="brand__heading">Ollie Read</h2>
        <h3 class="brand__subheading">PHP & Laravel Development</h3>

        <nav class="nav nav--sidebar-inverted nav--horizontal">
            <a href="/" class="nav__link" title="Who am I?">
                <i class="nav__icon fa-question"></i>
            </a>

            <a href="/" class="nav__link" title="Get in touch">
                <i class="nav__icon fa-envelope"></i>
            </a>

            <a href="/" class="nav__link" title="Ideas">
                <i class="nav__icon fa-lightbulb"></i>
            </a>
        </nav>
    </header>

    @include('layouts.partials.sidebar')

    <main class="page" id="app">

        <nav class="breadcrumbs">
            @yield('breadcrumbs')
        </nav>

        @yield('header')

        @yield('content')

    </main>

</div>

<script src="{{ mix('js/app.js') }}"></script>
@stack('page.footer')
</body>
</html>
