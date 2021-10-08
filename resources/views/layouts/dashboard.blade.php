<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="{{ __('pages.home.welcome') }}">

        <link rel="canonical" href="{{ LaravelLocalization::getNonLocalizedURL() }}" />

        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
        @endforeach

        @if(empty($title))
            <title>{{ __('navigation.dashboard').' | '.config('app.name') }}</title>
        @else
            <title>{{ $title.' | '.config('app.name') }}</title>
        @endif

        <link rel="shortcut icon" sizes="16x16" href="/favicon-16x16.png">
        <link rel="shortcut icon" sizes="32x32" href="/favicon-32x32.png">
        <link rel="shortcut icon" sizes="64x64" href="/favicon-64x64.png">
        <link rel="shortcut icon" sizes="96x96" href="/favicon-96x96.png">

        @stack('styles')
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @include('script.app')

        @routes()

        @stack('headerScripts')
    </head>
    <body class="bg-light">
        <div id="app" class="is-flex flex-col min-h-screen">
            <nz-dashboard-navbar :has-unread="{{ json_encode($hasUnreadNotifications) }}" inline-template>
                <nav class="navbar is-primary">
                    <div class="container is-fluid">
                        <div class="navbar-brand">
                            <a class="navbar-item" href="{{ url('/') }}" title="{{ config('app.name') }}">
                                <svg class="navbar-logo is-plane" version="1.1" viewBox="0 0 105 28" xmlns="http://www.w3.org/2000/svg">
                                    <g transform="matrix(1.7638 0 0 1.7637 -.00050103 .00040525)" font-size="25.4" font-weight="700" letter-spacing="0" word-spacing="0">
                                        <path d="m25.043 10.068c0 2.94 2.001 2.954 2.824 2.954 0.384 0 0.836-0.01 1.42-0.199-1.42-0.399-1.254-1.149-1.363-3.138v-4.041c-0.039-2.094-0.7-4.135-4.41-4.482 1.29 0.998 1.57 1.89 1.529 2.807zm8.928-0.952c0 1.18-0.227 1.918-0.894 1.918s-0.894-0.739-0.894-1.918c0-1.178 0.227-1.917 0.894-1.917s0.894 0.739 0.894 1.917zm2.995 0c0-2.457-1.448-3.905-3.889-3.905s-3.889 1.448-3.889 3.905c0 2.458 1.448 3.906 3.89 3.906 2.44 0 3.888-1.448 3.888-3.906z"/>
                                        <path d="m45.749 6.51c-0.31-0.084-0.414-0.3-0.85-0.367 3e-3 -0.099 0.174-0.322 0.595-0.428-0.27-0.084-0.464-0.051-0.624 0.024-0.315 0.15-0.495 0.468-0.86 0.39-0.677-0.821-1.827-0.914-2.702-0.918-1.817 0-3.506 0.767-3.506 2.627 0 0.753 0.369 1.449 1.05 1.86v0.057a2.139 2.139 0 0 0-1.05 1.861c0 1.69 1.433 1.846 2.427 1.846h2.186c0.298 0 0.468 0.071 0.468 0.27 0 0.497-1.107 0.568-1.774 0.568-0.965 0-2.129-0.17-2.81-0.554-0.643-0.195-1.134-0.49-1.701-0.736 0.19 0.61 2.286 2.738 4.284 2.853 1.842 0.105 4.329-0.44 4.329-2.713 0-1.392-1.022-2.074-2.456-2.074h-1.972c-0.412 0-0.994 0-0.994-0.44 0-0.227 0.185-0.37 0.34-0.44 0.327 0.085 0.866 0.128 1.179 0.128 2.228 0 3.505-1.094 3.505-2.443 0-0.327-0.099-0.696-0.283-0.966v-0.057c0.075-0.24 0.189-0.43 0.432-0.422 0.194 6e-3 0.492 0.18 0.787 0.074zm-2.224 0.734c0 0.398-0.213 0.867-0.724 0.867-0.51 0-0.724-0.47-0.724-0.867 0-0.398 0.213-0.866 0.724-0.866s0.724 0.468 0.724 0.866z"/>
                                        <path d="m52.791 9.614c0.057-0.327 0.1-0.725 0.1-1.122 0-2.031-1.335-3.281-3.322-3.281-2.384 0-3.803 1.491-3.803 3.905 0 2.543 1.504 3.906 4.002 3.906 1.022 0 2.044-0.199 2.952-0.625l-0.624-1.732a4.877 4.877 0 0 1-1.604 0.298c-0.951 0-1.718-0.327-1.845-1.35zm-4.158-1.321c0.028-0.739 0.326-1.378 0.837-1.378 0.412 0 0.667 0.469 0.667 1.179zm5.249 4.587h2.882v-5.013c0.17-0.114 0.51-0.228 0.908-0.228 0.326 0 0.681 0.071 1.008 0.284l0.851-2.57a2.029 2.029 0 0 0-0.752-0.142c-0.625 0-1.32 0.27-2.015 0.923h-0.057c0-0.54-0.312-0.923-0.98-0.923-0.099 0-0.24 0.014-0.326 0.028l-1.519 0.242z"/>
                                        <path d="m12.567 2.606c-0.798 0.818-0.779 1.309 0.079 2.069 0.857 0.76 1.372 0.742 2.169-0.075 0.797-0.818 0.779-1.309-0.079-2.069s-1.372-0.743-2.17 0.075z"/>
                                        <path d="m9.312 3.896c-0.909-1.34-3.116-1.306-3.471 1.253-0.23 1.258 3.34 0.518 4.35 3.713 0.167-3.602-0.314-4.038-0.879-4.966zm-4.985 8.006c2.64 0.535 5.55-2.399 5.55-2.399-4.72-0.96-5.65-0.037-6.42 0.366-0.846 1.013-0.496 1.811 0.87 2.033zm-4.288-0.398c-0.652-3.973 7.131-3.837 8.29-2.97-2.845-0.73-7.686-2.061-6.121-5.405 1.547-3.729 6.019-3.739 7.286-2.037 2.84 3.261 2.625 7.33 1.456 8.557-1.331 1.905-7.774 6.562-10.506 2.804a2.85 2.85 0 0 1-0.405-0.949z"/>
                                        <path d="m7.598 13.992c2.464 0.374 6.327-2.486 6.91-6.205 0.09-1.114-0.822-2.612-1.963-1.26-1.793 5.144-3.157 6.218-4.947 7.465zm12.834-5.168c0 1.179-0.227 1.917-0.894 1.917s-0.895-0.738-0.895-1.917c0-1.18 0.228-1.918 0.895-1.918s0.894 0.739 0.894 1.918zm2.995 0c0-2.457-1.448-3.906-3.89-3.906-2.44 0-3.888 1.449-3.888 3.906s1.447 3.905 3.889 3.905c2.44 0 3.889-1.448 3.889-3.905z"/>
                                    </g>
                                </svg>
                            </a>

                            <a href="{{ route('contributor.index') }}" class="navbar-item" title="{{ __('navigation.dashboard') }}">
                                @include('components.icon', ['icon' => 'dashboard'])
                            </a>

                            @can ('create', 'App\Announcement')
                                <a href="{{ route('admin.announcements.index') }}" class="navbar-item" title="{{ __('navigation.announcements') }}">
                                    @include('components.icon', ['icon' => 'bullhorn'])
                                </a>
                            @endcan

                            <a class="navbar-item" @click="toggleSidebar" title="{{ __('navigation.notifications') }}">
                                <span
                                    class="is-badge-danger is-badge-extra-small"
                                    :class="{'badge': hasUnreadNotifications}"
                                    data-badge
                                >
                                    @include('components.icon', ['icon' => 'bell'])
                                </span>
                            </a>

                            <div class="navbar-burger" :class="{ 'is-active': active }" @click="toggle">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>

                        <div class="navbar-menu" :class="{ 'is-active': active }">
                            <div class="navbar-end">
                                <a href="{{ route('preferences.index') }}"
                                    class="navbar-item"
                                    title="{{ __('navigation.preferences.index') }}">
                                    @include('components.icon', ['icon' => 'cog'])
                                    <span class="is-hidden-desktop">{{ __('navigation.preferences.index') }}</span>
                                </a>

                                <a href="{{ route('logout') }}"
                                    class="navbar-item"
                                    title="{{ auth()->user()->full_name }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <span>{{ __('navigation.logout') }}</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>

                    <nz-sidebar
                        :active="showSidebar"
                        :has-unread-notifications.sync="hasUnreadNotifications"
                        @close="showSidebar = false"
                    ></nz-sidebar>
                </nav>
            </nz-dashboard-navbar>

            <div class="secondary-navbar bg-white shadow">
                <div class="container is-fluid">
                    <div class="level">
                        <div class="level-left">
                            <div class="level-item">
                                @yield('breadcrumbs')
                            </div>
                        </div>

                        @hasSection('navigationActions')
                            <div class="level-right">
                                <div class="level-item">
                                    @yield('navigationActions')
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-light flex-1">
                <div class="container is-fluid p-4">
                    <div class="columns">
                        <div class="column is-3-tablet is-2-desktop">
                            @yield('sidebar', Menu::sidebar())
                        </div>

                        <div class="column">
                            @if(!empty($lastAnnouncement) && !$lastAnnouncement->isRead() && $lastAnnouncement->isTranslated())
                                <nz-announcement :announcement="{{ $lastAnnouncement }}"></nz-announcement>
                            @endif

                            @if (session('verified'))
                                <article class="message is-success shadow">
                                    <div class="message-body">
                                        {{ __('auth.verified') }}
                                    </div>
                                </article>
                            @endif

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.footer')

        </div>

        @include('cookieConsent::index')

        @stack('beforeScripts')
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('afterScripts')
    </body>
</html>
