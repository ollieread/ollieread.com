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

    <a href="{{ route('admin:user.index') }}" class="nav__link {{ request()->routeIs('admin:user.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-users"></i> Users
    </a>

    <a href="{{ route('admin:article.index') }}" class="nav__link {{ request()->routeIs('admin:article.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-newspaper"></i> Articles
    </a>

    <a href="{{ route('admin:topic.index') }}" class="nav__link {{ request()->routeIs('admin:topic.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-file-certificate"></i> Topics
    </a>

    <a href="{{ route('admin:version.index') }}" class="nav__link {{ request()->routeIs('admin:version.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-laptop-code"></i> Versions
    </a>

    <a href="#" class="nav__link">
        <i class="nav__icon fa-chalkboard-teacher"></i> Courses
    </a>

    <a href="#" class="nav__link">
        <i class="nav__icon fa-box"></i> Packages
    </a>

</nav>
