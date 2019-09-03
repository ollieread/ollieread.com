<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Multitenancy</title>

    <link rel="stylesheet" href="{{ mix('css/multitenancy.css') }}">
</head>
<body>

<div id="app">

    <section class="section">
        <div class="container">
            <header class="header">
                <div class="header__logo">
                    @include('svgs/multitenancy.svg')
                </div>
                <div class="header__branding">
                    <h1 class="header__branding-title">Multitenancy with <span class="text--laravel">Laravel</span></h1>
                    <p>
                        The premium course and package for creating multitenanted Laravel apps.
                    </p>
                </div>
            </header>
        </div>
    </section>

    <section class="section section--alternative text--center">
        <div class="container">
            <div class="section__content">
                <h2 class="section__heading">What is multitenancy?</h2>

                <p>
                    In its simplest form, multitenancy is a way of structuring a piece of software so that a single
                    instance, or
                    installation, runs as if it were multiple instances or installations.
                </p>

                <p>
                    There are many ways to go about achieving multitenancy, but there are some core archetypes, each
                    with
                    their
                    own variations and options, that make multitenancy a broader spectrum.
                </p>
            </div>
        </div>
    </section>

    <section class="section section--course">
        <div class="container">
            <div class="section__image">
                <img src="{{ asset('images/course.svg') }}" alt="">
            </div>
            <div class="section__content">
                <h2 class="section__heading">The Course</h2>
                <p>
                    The course breaks down all of the concepts behind multitenancy and explains them in an easily
                    digestable manner, as well walking you through how to implement then in a Laravel application.
                </p>

                <p>
                    The course also covers usecases for each concept, their compatiability with other involved concepts,
                    and examples of existing systems that make use of them.
                </p>
            </div>
        </div>
    </section>

    <section class="section section--package">
        <div class="container">
            <div class="section__content">
                <h2 class="section__heading">The Package</h2>
                <p>
                    The package, named Porter, is a configurable implementation of the course. It is designed to be used
                    when creating a full multitentancy sollution isn’t viable, whether that’s because of time, knowledge
                    or experience.
                </p>
                <p>
                    Porter is designed to be as non-invasive as possible, allowing you to write your application like
                    you would normally, with all the multitenancy functionality beind handled automatically.
                </p>
            </div>
            <div class="section__image">
                <img src="{{ asset('images/packages.svg') }}" alt="">
            </div>
        </div>
    </section>

    <section class="section section--alternative text--center">
        <div class="container">
            <section class="section__content">
                <h2 class="section__heading">But what is included?</h2>

                <p>
                    Both the course and package cover a whole range of topics and concepts, including the difference
                    branches, options and limitations that come from each of them.
                </p>
                <p>
                    Below is a breakdown of everything covered by both the course and package.
                </p>
            </section>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__content">
                <div class="flex flex-wrap">
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Introduction</h2>

                        <ul>
                            <li>What is multitenancy?</li>
                            <li>Why multitenancy?</li>
                            <li>What is multi-instance</li>
                            <li>Why multi-instance?</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Tenant Identification</h2>

                        <ul>
                            <li>Domains and/or Subdomains</li>
                            <li>Authentication</li>
                            <li>Session/Choice</li>
                            <li>URI Path</li>
                            <li>HTTP Headers</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Databases</h2>

                        <ul>
                            <li>Single database</li>
                            <li>Tenant migrations</li>
                            <li>Tenant Seeding</li>
                            <li>Database per tenant</li>
                            <li>Creating a tenant database</li>
                            <li>Creating tenant database credentials</li>
                            <li>Sharing global data</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Overriding the Core</h2>

                        <ul>
                            <li>Cookies</li>
                            <li>Sessions</li>
                            <li>Cache</li>
                            <li>Queues</li>
                            <li>File Storage</li>
                            <li>Services</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Users</h2>

                        <ul>
                            <li>Tenant users</li>
                            <li>Shared users</li>
                            <li>The Slack approach</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Configuration</h2>

                        <ul>
                            <li>Tenant app keys</li>
                            <li>Tenant config override</li>
                            <li>3rd party credentials</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Templating/Views</h2>

                        <ul>
                            <li>Preset themes</li>
                            <li>Custom themes</li>
                            <li>CSS root variables</li>
                            <li>Pre-processor styling (scss)</li>
                            <li>Blade namespacing</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Assets</h2>

                        <ul>
                            <li>Public tenant asset</li>
                            <li>Private tenant assets</li>
                            <li>Proxying CDNs</li>
                            <li>Locking down asset access</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Features/modules</h2>

                        <ul>
                            <li>Writing generic modules</li>
                            <li>Limiting tenant access to features</li>
                        </ul>
                    </div>
                    <div class="w-1/3 mb-6">
                        <h2 class="section__heading">Misc</h2>

                        <ul>
                            <li>Tenant manager</li>
                            <li>Routing</li>
                            <li>Tenant middleware & priorities</li>
                            <li>Application lifecycle</li>
                            <li>Apache virtual hosts</li>
                            <li>Nginx server blocks</li>
                            <li>Tenant DNS</li>
                            <li>Examples</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
</body>
</html>
