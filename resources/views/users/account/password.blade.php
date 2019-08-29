@extends('layouts.main')

@section('sidebar')

    @include('users.partials.sidebar')

@endsection

@section('content')

    <form action="#" method="post" class="box">
        @csrf

        <header class="box__header">

            <h1 class="box__header-title">My Account Password</h1>

        </header>

        <main class="box__body">

            <div class="form">

                @include('components.alerts', ['context' => 'account'])

                @if ($user->password)
                    <div class="input {{ $errors->has('current_password') ? 'input--invalid' : '' }}">
                        <label for="user-current_password" class="input__label">Current Password</label>
                        <input type="password" name="current_password" id="user-current_password" required
                               class="input__field"
                               placeholder="Enter your current password">
                        {!! $errors->first('current_password', '<div class="input__feedback">:message</div>') !!}
                    </div>
                @else
                    <div class="notice notice--info">
                        <p>You haven't set a password yet, so it's probably best that you set one now.</p>
                    </div>
                @endif

                <div class="input input--stack {{ $errors->has('password') ? 'input--invalid' : '' }}">
                    <label for="user-password"
                           class="input__label {{ $user->password ? 'input__label--sronly' : '' }}">New
                        Password</label>
                    <input type="password" name="password" id="user-password" required class="input__field"
                           placeholder="Enter your new password">

                    <label for="user-password_confirmation" class="input__label--sronly">Confirm New
                        Password</label>
                    <input type="password" name="password_confirmation" id="user-password_confirmation" required
                           class="input__field" placeholder="Confirm your new password">
                    {!! $errors->first('password', '<div class="input__feedback">:message</div>') !!}
                    {!! $errors->first('password_confirmation', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        Passwords must be at least 8 characters
                    </div>
                </div>

            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
            <a href="#" class="button button--text">Cancel</a>
        </footer>

    </form>

@endsection
