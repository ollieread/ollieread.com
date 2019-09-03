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

    {{--
    <a href="{{ route('users:account.details.edit') }}"
       class="nav__link {{ request()->routeIs('users:account.details.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-users"></i> My Team
    </a>--}}

    <a href="{{ route('users:account.password.edit') }}"
       class="nav__link {{ request()->routeIs('users:account.password.*') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-key"></i> Change Password
    </a>

    @can('MANAGE_API_KEYS')
        <a href="{{ route('users:account.api.edit') }}"
           class="nav__link {{ request()->routeIs('users:account.api.*') ? 'nav__link--active' : '' }}">
            <i class="nav__icon fa-id-badge"></i> API Keys
        </a>
    @endcan

</nav>

@canany(['BILLING_DETAILS', 'BILLING_PURCHASES', 'BILLING_SUBSCRIPTIONS'])
    <nav class="nav nav--sidebar">

        <h5 class="nav__heading">Billing</h5>

        @can('BILLING_DETAILS')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-credit-card-front"></i> Billing Details
            </a>
        @endcan

        @can('BILLING_PURCHASES')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-receipt"></i> Purchases
            </a>
        @endcan

        @can('BILLING_SUBSCRIPTIONS')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-file-invoice"></i> Subscriptions
            </a>
        @endcan

    </nav>
@endcanany

@canany(['PRODUCT_LICENSES', 'PRODUCT_COURSES', 'PRODUCT_PROJECTS'])
    <nav class="nav nav--sidebar">

        <h5 class="nav__heading">Products</h5>

        @can('PRODUCT_LICENSES')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-file-certificate"></i> Licenses
            </a>
        @endcan

        @can('PRODUCT_COURSES')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-chalkboard"></i> Courses
            </a>
        @endcan

        @can('PRODUCT_PROJECTS')
            <a href="/" class="nav__link">
                <i class="nav__icon fa-project-diagram"></i> Projects
            </a>
        @endcan

    </nav>
@endcanany
