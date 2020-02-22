@extends('layouts.single')

@section('content')

    <form action="{{ route('users:password.forgot.store') }}" method="post" class="box box--split box--small">
        @csrf

        <section class="box__body">

            @include('components.alerts', ['context' => 'password'])

            <div class="form form--inset">

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email') }}">
                    {!! $errors->first('email', '<div class="input__feedback">:message</div>') !!}
                </div>

                <div class="button__bar button__bar--centered">
                    <button type="submit" class="button button--primary">
                        <i class="button__icon fa-password"></i>
                        Submit
                    </button>
                </div>

                <p><a href="{{ route('users:sign-in.create') }}" class="link link--subtle">Remembered your password?</a></p>
            </div>

        </section>

    </form>

@endsection
