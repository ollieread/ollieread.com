@extends('layouts.single')

@section('title', __('Not Found'))
@section('content')

    <article class="box box--small box--footerless">
        <header class="box__header">
            <h1 class="box__header-title">Page Expired</h1>
        </header>

        <main class="box__body text-center">
            <p class="text--spaced">
                I'm afraid the request has expired, please go back and try again.
            </p>
        </main>

    </article>

@endsection
