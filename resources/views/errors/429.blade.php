@extends('layouts.single')

@section('title', __('Not Found'))
@section('content')

    <article class="box box--small box--footerless">
        <header class="box__header">
            <h1 class="box__header-title">Too many Requests</h1>
        </header>

        <main class="box__body text-center">
            <p class="text--spaced">
                Are you trying to DDOS me?
            </p>
        </main>

    </article>

@endsection
