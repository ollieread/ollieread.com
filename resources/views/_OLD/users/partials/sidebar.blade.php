<nav class="nav nav--sidebar">

    <a href="{{ route('site:home') }}"
       class="nav__link {{ request()->routeIs('site:home') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-arrow-left"></i> Back to Site
    </a>

</nav>

<nav class="nav nav--sidebar">

    <h5 class="nav__heading">My Account</h5>

    <a href="{{ route('users:account.details.edit') }}"
       class="nav__link {{ request()->routeIs('users:account.details.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-user"></i> My Details
    </a>

    <a href="{{ route('users:account.social.edit') }}"
       class="nav__link {{ request()->routeIs('users:account.social.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-user-plus"></i> Social Accounts
    </a>

    <a href="{{ route('users:account.password.edit') }}"
       class="nav__link {{ request()->routeIs('users:account.password.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-key"></i> Change Password
    </a>

</nav>
