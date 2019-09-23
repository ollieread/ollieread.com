@extends('users.admin.layout')

@section('breadcrumbs')
    <a href="{{ route('admin:dashboard') }}" class="breadcrumb">Admin</a>
    <span class="breadcrumb breadcrumb--active">Users</span>
@endsection

@section('content')

    <header class="page__header">
        <h2 class="page__header-heading">Users</h2>
    </header>

    @include('components.alerts', ['context' => 'admin.users'])

    <section class="box box--headerless {{ ! $users->hasPages() ? 'box--footerless' : '' }}">
        <main class="box__body box__body--flush">
            <table class="table">
                <thead class="table__header">
                <tr class="table__row">
                    <th class="table__cell">Username</th>
                    <th class="table__cell">Email</th>
                    <th class="table__cell table__cell--actions"></th>
                </tr>
                </thead>
                <tbody class="table__body">
                @foreach ($users as $user)
                    <tr class="table__row">
                        <td class="table__cell">
                            {{ $user->username }}
                            @if ($user->active)
                                <i class="fa fa-check text--success" data-tippy-content="User is active"></i>
                            @else
                                <i class="fa fa-times text--error" data-tippy-content="User is inactive"></i>
                            @endif
                        </td>
                        <td class="table__cell">
                            {{ $user->email }}
                            @if ($user->verified)
                                <i class="fa fa-check text--success" data-tippy-content="User is verified"></i>
                            @else
                                <i class="fa fa-times text--error" data-tippy-content="User is unverified"></i>
                            @endif
                        </td>
                        <td class="table__cell table__cell--center">
                            <a href="{{ route('admin:user.edit', $user->id) }}"
                               class="button button--icon button--small" data-tippy-content="Edit user">
                                <span class="sr-only">Edit</span>
                                <i class="button__icon fa-user-edit"></i>
                            </a>
                            @if ($user->active && $user->verified)
                                <a href="{{ route('admin:user.reset', $user->id) }}" class="button button--icon button--small"
                                   data-tippy-content="Send a password reset">
                                    <span class="sr-only">Password Reset</span>
                                    <i class="button__icon fa-user-lock"></i>
                                </a>
                            @endif
                            @if (! $user->verified)
                                <a href="{{ route('admin:user.verify', $user->id) }}" class="button button--icon button--small"
                                   data-tippy-content="Verify the user">
                                    <span class="sr-only">Verify User</span>
                                    <i class="button__icon fa-user-check"></i>
                                </a>
                                <a href="{{ route('admin:user.resend', $user->id) }}" class="button button--icon button--small"
                                   data-tippy-content="Resend user verification email">
                                    <span class="sr-only">Resend User Verification</span>
                                    <i class="button__icon fa-envelope"></i>
                                </a>
                            @endif
                            <a href="{{ route('admin:user.toggle', $user->id) }}" class="button button--icon button--small"
                               data-tippy-content="{{ $user->active ? 'Disable' : 'Enable' }} user">
                                @if ($user->active)
                                    <span class="sr-only">Disable</span>
                                    <i class="button__icon fa-user-minus"></i>
                                @else
                                    <span class="sr-only">Enable</span>
                                    <i class="button__icon fa-user-plus"></i>
                                @endif
                            </a>
                            <a href="#" class="button button--icon button--small" data-tippy-content="Delete user">
                                <span class="sr-only">Delete</span>
                                <i class="button__icon fa-user-slash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
        @if ($users->hasPages())
            <footer class="box__footer box__footer--secondary">
                {!! $users->links() !!}
            </footer>
        @endif
    </section>

@endsection
