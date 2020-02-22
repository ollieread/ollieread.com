@extends('layouts.main')

@section('sidebar')

    @include('users.partials.sidebar')

@endsection

@section('content')

    <form action="#" method="post" class="box">
        @csrf

        <header class="box__header">

            <h1 class="box__header-title">My Social Accounts</h1>

        </header>

        <main class="box__body">

            @include('components.alerts', ['context' => 'account'])

            @include('users.account.partials.social-box', ['social' => $socials->get('twitter'), 'provider' => 'twitter'])
            @include('users.account.partials.social-box', ['social' => $socials->get('google'), 'provider' => 'google'])
            @include('users.account.partials.social-box', ['social' => $socials->get('github'), 'provider' => 'github'])
            @include('users.account.partials.social-box', ['social' => $socials->get('discord'), 'provider' => 'discord'])

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
        </footer>

    </form>

@endsection
