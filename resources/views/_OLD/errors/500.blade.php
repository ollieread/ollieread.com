@extends('layouts.single')

@section('title', __('Not Found'))
@section('content')

    <article class="box box--small box--footerless">
        <header class="box__header">
            <h1 class="box__header-title">Server Error</h1>
        </header>

        <main class="box__body text-center">
            <p class="text--spaced">
                My bad, seems like I broke something.
            </p>
        </main>

    </article>

@endsection
