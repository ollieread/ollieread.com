<aside class="sidebar">
    <span class="sidebar__menu-link" id="sidebar__close">
        <i class="fa fa-fw fa-times"></i>
    </span>

    <header class="sidebar__header">
        <a href="/" class="logo"></a>
        <h2 class="sidebar__header-heading">Ollie Read</h2>
        <h3 class="sidebar__header-subheading">PHP & Laravel Development</h3>

        <nav class="nav nav--sidebar-inverted nav--horizontal">
            <a href="/" class="nav__link" title="Who am I?">
                <i class="nav__icon fa-question"></i>
            </a>

            <a href="/" class="nav__link" title="Get in touch">
                <i class="nav__icon fa-envelope"></i>
            </a>

            <a href="/" class="nav__link" title="Ideas">
                <i class="nav__icon fa-lightbulb"></i>
            </a>
        </nav>
    </header>

    <main class="sidebar__content">

        @yield('sidebar')

    </main>

    <footer class="sidebar__footer">

        <nav class="nav nav--sidebar-inverted">
            @can('admin')
                <a href="#" class="nav__link">
                    <i class="nav__icon fa-user-crown"></i> Admin
                </a>
            @endcan

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
                <a href="{{ route('users:account.details.edit') }}" class="nav__link">
                    <i class="nav__icon fa-user"></i> My Account
                </a>
                <a href="{{ route('users:sign-out') }}" class="nav__link">
                    <i class="nav__icon fa-sign-out"></i> Sign Out
                </a>
            @endauth

        </nav>

    </footer>

</aside>
