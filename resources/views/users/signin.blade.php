@extends('layouts.single')

@section('content')

    <form action="{{ route('users:sign-in.store') }}" method="post" class="box box--split box--small">
        @csrf

        <section class="box__body">

            @include('components.alerts', ['context' => 'sign-in'])

            <p>
                Sign in with:
            </p>

            <div class="button__bar button__bar--centered">
                <a href="{{ route('users:social.auth', 'twitter') }}" class="button">
                    <i class="button__icon button__icon--twitter"></i>
                    Twitter
                </a>

                <a href="{{ route('users:social.auth', 'google') }}" class="button">
                    <i class="button__icon button__icon--google"></i>
                    Google
                </a>

                <a href="{{ route('users:social.auth', 'github') }}" class="button">
                    <i class="button__icon button__icon--github"></i>
                    GitHub
                </a>

                <a href="{{ route('users:social.auth', 'discord') }}" class="button">
                    <i class="button__icon button__icon--discord"></i>
                    Discord
                </a>
            </div>

            <div class="form form--inset">

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email') }}">
                    {!! $errors->first('email', '<div class="input_feedback">:message</div>') !!}
                </div>

                <div class="input {{ $errors->has('password') ? 'input--invalid' : '' }}">
                    <label for="user-password" class="input__label">Password</label>
                    <input type="password" name="password" id="user-password" required class="input__field"
                           placeholder="Enter a password">
                    {!! $errors->first('password', '<div class="input_feedback">:message</div>') !!}
                    <div class="field__info"><a href="#" class="link link--subtle">Forgot your password?</a></div>
                </div>

                <div class="button__bar button__bar--centered">
                    <button type="submit" class="button button--primary">
                        <i class="button__icon fa-user"></i>
                        Sign In
                    </button>
                </div>

                <p><a href="{{ route('users:register.create') }}" class="link link--subtle">Don't have an account?</a></p>
            </div>

        </section>

    </form>

@endsection
