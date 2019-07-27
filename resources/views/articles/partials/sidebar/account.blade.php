<nav class="nav nav--sidebar">

    <h5 class="nav__heading">Account</h5>

    @guest('user')
        <a href="{{ route('users:sign-in.create') }}"
           class="nav__link {{ request()->routeIs('user:sign-in.create') ? 'nav__link--active' : '' }}">
            <i class="nav__icon fa-sign-in"></i> Sign In
        </a>

        <a href="{{ route('users:register.create') }}"
           class="nav__link {{ request()->routeIs('user:register.create') ? 'nav__link--active' : '' }}">
            <i class="nav__icon fa-user-plus"></i> Register
        </a>
    @endguest
    @auth('user')
        <a href="#" class="nav__link">
            <i class="nav__icon fa-user"></i> My Account
        </a>
        <a href="#" class="nav__link">
            <i class="nav__icon fa-sign-out"></i> Sign Out
        </a>
    @endauth

</nav>