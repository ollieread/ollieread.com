@extends('layouts.main')

@push('page.header')
    @include('partials.main-meta')
@endpush

@section('sidebar')

    @include('articles.partials.sidebar')

@endsection

@section('header')
    <header class="page__header">
        <h1 class="page__header-heading">Hello there!</h1>
        <p class="page__header-text">
            I'm Ollie Read and I've been a full stack PHP developer since July 2007, with a heavy focus on Laravel
            since 2012.
        </p>

        <p class="page__header-text">
            I've had the privilege of working on some interesting projects for the likes of <strong>Miniclip</strong>,
            <strong>Cancer
                Research UK</strong>, <strong>The Open University</strong>, <strong>Domino's</strong> and even <strong>the
                president of Nigeria</strong>
            (yes really).
        </p>

        <p class="page__header-text">
            I'm a prolific writer of code and I like to share both my creations and my knowledge, be it by way of an
            <a href="{{ route('articles:index') }}" class="link">article</a>, course, package
            or just answering questions on <a href="https://stackoverflow.com/users/3104359/ollieread" class="link">StackOverflow</a>.
        </p>

        <p class="page__header-text">
            I'm currently working on a <a href="https://multitenancy.dev" target="_blank" rel="noopener" class="link">multitenancy
                with Laravel</a> course and Porter,
            a premium laravel multitenancy package.
        </p>

        <p class="page__header-text">
            As well as offering bespoke builds for clients through <a href="https://sprocketbox.io" class="link">Sprocketbox</a>,
            I also offer a number of other services such as 1 on 1 mentoring, code
            reviews, and/or regular
            Google hangout meetings to help advise and guide.
        </p>

        {{--<p class="page__header-text"><a href="#" class="link">Read more...</a></p>--}}

        <div class="button__bar button__bar--slim">
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
