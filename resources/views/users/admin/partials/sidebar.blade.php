<nav class="nav nav--sidebar">

    <a href="{{ route('site:home') }}" class="nav__link">
        <i class="nav__icon fa-arrow-left"></i> Back to Site
    </a>

</nav>

<nav class="nav nav--sidebar">

    <h5 class="nav__heading">Admin</h5>

    <a href="{{ route('admin:dashboard') }}"
       class="nav__link {{ request()->routeIs('admin:dashboard') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-tachometer"></i> Dashboard
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-users"></i> Users
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-newspaper"></i> Articles
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-chalkboard-teacher"></i> Courses
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-box"></i> Packages
    </a>

</nav>
