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

                <div class="input {{ $errors->has('current_password') ? 'input--invalid' : '' }}">
                    <label for="user-current_password" class="input__label">Change Password</label>
                    <input type="password" name="current_password" id="user-current_password" required class="input__field"
                           placeholder="Enter your current password">
                    {!! $errors->first('current_password', '<div class="input_feedback">:message</div>') !!}
                </div>

                <div class="input input--stack {{ $errors->has('new_password') ? 'input--invalid' : '' }}">
                    <label for="user-new_password" class="input__label--sronly">New Password</label>
                    <input type="password" name="new_password" id="user-new_password" required class="input__field"
                           placeholder="Enter your new password">

                    <label for="user-new_password_confirmation" class="input__label--sronly">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="user-new_password_confirmation" required
                           class="input__field" placeholder="Confirm your new password">
                    {!! $errors->first('new_password', '<div class="input_feedback">:message</div>') !!}
                    {!! $errors->first('new_password_confirmation', '<div class="input_feedback">:message</div>') !!}
                    <div class="input__info">
                        Passwords must be at least 8 characters
                    </div>
                </div>
                @if (! $socials->isEmpty())
                    <div class="input">
                        <label for="" class="input__label">Social Accounts</label>
                        @if ($socials->has('twitter'))
                            <div class="avatar avatar--spaced">
                                <div class="avatar__social avatar__social--twitter"></div>
                                <img src="{{ $socials->get('twitter')->avatar }}" alt="" class="avatar__image">
                            </div>
                        @endif
                        @if ($socials->has('google'))
                            <div class="avatar avatar--spaced">
                                <div class="avatar__social avatar__social--google"></div>
                                <img src="{{ $socials->get('google')->avatar }}" alt="" class="avatar__image">
                            </div>
                        @endif
                        @if ($socials->has('github'))
                            <div class="avatar avatar--spaced">
                                <div class="avatar__social avatar__social--github"></div>
                                <img src="{{ $socials->get('github')->avatar }}" alt="" class="avatar__image">
                            </div>
                        @endif
                    </div>
                @endif

                @if ($socials->count() < 3)

                    <div class="input">
                        <label for="" class="input__label">Add Social Accounts</label>
                        <div class="button__bar">
                            @if (! $socials->has('twitter'))
                                <a href="{{ route('users:social.auth', ['twitter', 'redirect_to' => route('users:account.create')]) }}"
                                   class="button">
                                    <i class="button__icon button__icon--twitter"></i>
                                    Twitter
                                </a>
                            @endif
                            @if (! $socials->has('google'))
                                <a href="{{ route('users:social.auth', ['google', 'redirect_to' => route('users:account.create')]) }}"
                                   class="button">
                                    <i class="button__icon button__icon--google"></i>
                                    Google
                                </a>
                            @endif
                            @if (! $socials->has('github'))
                                <a href="{{ route('users:social.auth', ['github', 'redirect_to' => route('users:account.create')]) }}"
                                   class="button">
                                    <i class="button__icon button__icon--github"></i>
                                    GitHub
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </main>

        <footer class="box__footer box__footer--secondary">
            <button type="submit" class="button button--primary">Update</button>
            <a href="#" class="button button--text">Cancel</a>
        </footer>

    </form>

@endsection