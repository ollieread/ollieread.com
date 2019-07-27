@extends('layouts.main')

@section('sidebar')

    <nav class="nav nav--sidebar">

        <a href="{{ route('site:home') }}"
           class="nav__link {{ request()->routeIs('site:home') ? 'nav__link--active' : '' }}">
            <i class="nav__icon fa-home"></i> Home
        </a>

        <a href="{{ route('articles:index') }}"
           class="nav__link {{ request()->routeIs('articles:index') ? 'nav__link--active' : '' }}">
            <i class="nav__icon fa-newspaper"></i> Articles
        </a>

        <a href="/" class="nav__link">
            <i class="nav__icon fa-chalkboard-teacher"></i> Courses
        </a>

        <a href="/" class="nav__link">
            <i class="nav__icon fa-box"></i> Packages
        </a>

        <a href="/" class="nav__link">
            <i class="nav__icon fa-code"></i> Services
        </a>

    </nav>

@endsection

@section('header')
    <header class="page__header">
        <h1 class="page__header-heading">Hello there!</h1>
        <p class="page__header-text">
            I'm Ollie Read and I've been a full stack PHP developer since July 2007, with a heavy focus on Laravel
            since 2012.
        </p>

        <p class="page__header-text">
            I've had the privilege of working on some interesting projects for the likes of Miniclip, Cancer
            Research
            UK, The Open University, Domino's and even the president of Nigeria (yes really).
        </p>

        <p class="page__header-text">
            I'm a prolific writer of code and I like to share both my creations and my knowledge, be it by way of an
            <a href="/" class="link">article</a>, <a href="#" class="link">course</a>, <a href="#" class="link">package</a>
            or just answering questions on <a href="https://stackoverflow.com/users/3104359/ollieread" class="link">StackOverflow</a>.
        </p>

        <p class="page__header-text">
            I'm currently working on a <a href="#" class="link">multi-tenancy with Laravel</a> course and <a href="#" class="link">Porter</a>
            a premium laravel multi-tenancy package.
        </p>

        <p class="page__header-text">
            As well as offering bespoke builds for clients through <a href="https://sprocketbox.io" class="link">Sprocketbox</a>,
            I also offer a number of other <a href="#" class="link">services</a> such as 1 on 1 mentoring, code
            reviews, and/or regular
            Google hangout meetings to help advise and guide.
        </p>

        <p class="page__header-text"><a href="#" class="link">Read more...</a></p>

        <div class="button__bar button__bar--flexible button__bar--slim button__bar--centered">
            <a href="https://github.com/ollieread" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--github"></i>
            </a>
            <a href="https://twitter.com/ollieread" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--twitter"></i>
            </a>
            <a href="https://www.linkedin.com/in/ollieread/" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--linkedin"></i>
            </a>
            <a href="https://reddit.com/u/ollieread" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--reddit"></i>
            </a>
            <a href="https://keybase.io/ollieread" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--keybase"></i>
            </a>
            <a href="https://stackoverflow.com/users/3104359/ollieread" class="button button--icon" target="_blank">
                <i class="button__icon button__icon--stack"></i>
            </a>
        </div>
    </header>
@endsection

@section('content')



@endsection