@extends('layouts.main')

@section('sidebar')

    @include('users.partials.sidebar')

@endsection

@section('content')

    <form action="{{ route('users:account.details.update') }}" method="post" class="box">
        @csrf
        <header class="box__header">

            <h1 class="box__header-title">My Account Details</h1>

        </header>

        <main class="box__body">

            @include('components.alerts', ['context' => 'account'])

            <div class="form">
                <div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
                    <label for="user-name" class="input__label">Name</label>
                    <input type="text" name="name" id="user-username" class="input__field"
                           placeholder="Enter your name" value="{{ old('name', $user->name) }}">
                    {!! $errors->first('name', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        Your real name
                    </div>
                </div>

                <div class="input {{ $errors->has('username') ? 'input--invalid' : '' }}">
                    <label for="user-username" class="input__label">Username</label>
                    <input type="text" disabled id="user-username" required class="input__field"
                           placeholder="Enter your username" value="{{ old('username', $user->username) }}">
                    {!! $errors->first('username', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        You cannot change your username
                    </div>
                </div>

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label input__label--required">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email', $user->email) }}">
                    {!! $errors->first('email', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        For verification and occasional contact
                    </div>
                </div>

                <div class="input {{ $errors->has('interests') ? 'input--invalid' : '' }}">
                    <label for="user-current_password" class="input__label">Newsletter Interests</label>

                    <div class="grid grid--3">
                        <div class="column">
                            <label class="input__label">
                                <input type="checkbox" name="interests[]" value="mwl" {{ in_array('mwl', old('interests', $user->interests ?? []), true) ? 'checked' : '' }}> Multitenancy With Laravel
                            </label>
                            <div class="input__info">Keep up to date with the Multitenancy with Laravel course.</div>
                        </div>
                        <div class="column">
                            <label class="input__label">
                                <input type="checkbox" name="interests[]" value="ksa" {{ in_array('ksa', old('interests', $user->interests ?? []), true) ? 'checked' : '' }}> Kitchen Sink Academy
                            </label>
                            <div class="input__info">Keep up to date wih the Kitchen Sink Academy lessons.</div>
                        </div>
                        <div class="column">
                            <label class="input__label">
                                <input type="checkbox" name="interests[]" value="porter" {{ in_array('porter', old('interests', $user->interests ?? []), true) ? 'checked' : '' }}> Porter
                            </label>
                            <div class="input__info">Keep up to date with the Porter package.</div>
                        </div>
                        <div class="column">
                            <label class="input__label">
                                <input type="checkbox" name="interests[]" value="surveys" {{ in_array('surveys', old('interests', $user->interests ?? []), true) ? 'checked' : '' }}> Surveys
                            </label>
                            <div class="input__info">Be notified of new surveys to take part in.</div>
                        </div>
                        <div class="column">
                            <label class="input__label">
                                <input type="checkbox" name="interests[]" value="monthly" {{ in_array('monthly', old('interests', $user->interests ?? []), true) ? 'checked' : '' }}> Monthly Updates
                            </label>
                            <div class="input__info">Receive a monthly digest from ollieread.com.</div>
                        </div>
                    </div>
                </div>

                <div class="input {{ $errors->has('current_password') ? 'input--invalid' : '' }}">
                    <label for="user-current_password" class="input__label">Current Password</label>
                    <input type="password" name="current_password" id="user-current_password" required
                           class="input__field"
                           placeholder="Enter your current password">
                    {!! $errors->first('current_password', '<div class="input__feedback">:message</div>') !!}
                    <div class="input__info">
                        You need to confirm your current password to update your account details
                    </div>
                </div>

            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
        </footer>

    </form>

@endsection
