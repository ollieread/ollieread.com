@extends('layouts.single')

@section('title', __('Not Found'))
@section('content')

    <article class="box box--small box--footerless">
        <header class="box__header">
            <h1 class="box__header-title">Maintenance Mode</h1>
        </header>

        <main class="box__body text-center">
            <p class="text--spaced">
                The site is currently in maintenance mode and will back online asap.
            </p>
        </main>

    </article>

@endsection
