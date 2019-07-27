@extends('layouts.main')

@section('sidebar')

    @include('users.partials.sidebar')

@endsection

@section('content')

    <form action="#" method="post" class="box">
        <header class="box__header">

            <h1 class="box__header-title">My Account</h1>

        </header>

        <main class="box__body">

            <div class="form">
                <div class="input {{ $errors->has('name') ? 'input--invalid' : '' }}">
                    <label for="user-name" class="input__label">Name</label>
                    <input type="text" name="name" id="user-username" required class="input__field"
                           placeholder="Enter your name" value="{{ old('name', $user->name) }}">
                    {!! $errors->first('name', '<div class="input_feedback">:message</div>') !!}
                    <div class="input__info">
                        Your real name
                    </div>
                </div>

                <div class="input {{ $errors->has('username') ? 'input--invalid' : '' }}">
                    <label for="user-username" class="input__label input__label--required">Username</label>
                    <input type="text" name="username" id="user-username" required class="input__field"
                           placeholder="Enter your username" value="{{ old('username', $user->username) }}">
                    {!! $errors->first('username', '<div class="input_feedback">:message</div>') !!}
                    <div class="input__info">
                        Your unique display name
                    </div>
                </div>

                <div class="input {{ $errors->has('email') ? 'input--invalid' : '' }}">
                    <label for="user-email" class="input__label input__label--required">Email</label>
                    <input type="email" name="email" id="user-email" required class="input__field"
                           placeholder="Enter your email address" value="{{ old('email', $user->email) }}">
                    {!! $errors->first('email', '<div class="input_feedback">:message</div>') !!}
                    <div class="input__info">
                        For verification and occasional contact
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
