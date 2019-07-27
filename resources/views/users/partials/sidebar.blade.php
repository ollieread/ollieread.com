<nav class="nav nav--sidebar">

    <a href="{{ route('site:home') }}"
       class="nav__link {{ request()->routeIs('site:home') ? 'nav__link--active' : '' }}">
        <i class="nav__icon fa-arrow-left"></i> Back to Site
    </a>

</nav>

<nav class="nav nav--sidebar">

    <h5 class="nav__heading">My Account</h5>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-user"></i> My Details
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-user-plus"></i> Social Accounts
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-users"></i> My Team
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-key"></i> Change Password
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-id-badge"></i> API Keys
    </a>

</nav>

<nav class="nav nav--sidebar">

    <h5 class="nav__heading">Billing</h5>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-credit-card-front"></i> Billing Details
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-receipt"></i> Purchases
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-file-invoice"></i> Subscriptions
    </a>

</nav>

<nav class="nav nav--sidebar">

    <h5 class="nav__heading">Products</h5>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-file-certificate"></i> Licenses
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-chalkboard"></i> Courses
    </a>

    <a href="/" class="nav__link">
        <i class="nav__icon fa-project-diagram"></i> Projects
    </a>

</nav>