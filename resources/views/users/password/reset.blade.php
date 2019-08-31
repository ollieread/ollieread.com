@extends('layouts.single')

@section('content')

    <form action="{{ route('users:password.reset.store') }}" method="post" class="box box--split box--small">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <section class="box__body">

            @include('components.alerts', ['context' => 'password'])

            <div class="form form--inset">

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email') }}">
                    {!! $errors->first('email', '<div class="input__feedback">:message</div>') !!}
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
                        <i class="button__icon fa-password"></i>
                        Submit
                    </button>
                </div>

                <p><a href="{{ route('users:sign-in.create') }}" class="link link--subtle">Remembered your password?</a>
                </p>
            </div>

        </section>

    </form>

@endsection
