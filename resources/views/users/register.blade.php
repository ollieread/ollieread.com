@extends('layouts.single')

@section('content')

    <form action="{{ route('users:register.store') }}" method="post" class="box box--split">
        @csrf

        <section class="box__body box__body--cta">

            <h1 class="box__body-heading">
                Register for an account
            </h1>

            <ul class="list list--light">
                <li class="list__item">Comment on Articles</li>
                <li class="list__item">Vote on upcoming content</li>
                <li class="list__item">Submit suggestions & ideas</li>
                <li class="list__item">Receive notifications of new content</li>
                <li class="list__item">Purchase courses & other services</li>
            </ul>
        </section>

        <section class="box__body">

            @include('components.alerts', ['context' => 'register'])

            <p>
                Sign up with:
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
                <div class="input {{ $errors->has('username') ? 'input--invalid' : '' }}">
                    <label for="user-username" class="input__label">Username</label>
                    <input type="text" name="username" id="user-username" required class="input__field"
                           placeholder="Enter your username" value="{{ old('username') }}">
                    {!! $errors->first('username', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        Your unique display name
                    </div>
                </div>

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email') }}">
                    {!! $errors->first('email', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        For verification and occasional contact
                    </div>
                </div>

                <div class="input input--stack {{ $errors->has('password') ? 'input--invalid' : '' }}">
                    <label for="user-password" class="input__label">Password</label>
                    <input type="password" name="password" id="user-password" required class="input__field"
                           placeholder="Enter a password">

                    <label for="user-password_confirmation" class="input__label--sronly">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="user-password_confirmation" required
                           class="input__field" placeholder="Confirm your password">
                    {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
                    {!! $errors->first('password_confirmation', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        Passwords must be at least 8 characters
                    </div>
                </div>

                <div class="button__bar button__bar--centered">
                    <button type="submit" class="button button--primary">
                        <i class="button__icon fa-user-plus"></i>
                        Register Your Account
                    </button>
                </div>

                <p><a href="{{ route('users:sign-in.create') }}" class="link link--subtle">Already have an account?</a></p>
            </div>

        </section>

    </form>

@endsection
