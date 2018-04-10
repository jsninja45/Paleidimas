<div class="site-header">

    {{-- small menu --}}
    <div class="top-header">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    @if (Route::is('homepage'))
                        <div class="social">
                            <div class="fb-like" data-href="https://speechtotextservice.com" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                            <div class="g-plusone" data-size="medium" data-href="https://speechtotextservice.com"></div>
                        </div>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-8 col-md-9">
                    <div class="menu">
                        @if (Auth::check())

                            @if (Auth::user()->hasRole('admin', 'editor', 'transcriber'))
                                <a class="menu-link menu-link-admin" href="{{ url('admin') }}">Work</a>
                            @endif
                            <a class="menu-link menu-link-settings" href="{{ route('user_settings', [Auth::id()]) }}">Account settings</a>
                            <a class="menu-link menu-link-orders" href="{{ route('user_orders', [Auth::id()]) }}">My orders</a>
                            <a class="menu-link menu-link-place_order" href="{{ route('upload') }}">Place order</a>
                            <a class="menu-link menu-link-logout" href="{{ url('auth/logout') }}">Logout</a>
                        @else
                            <a class="menu-link menu-link-login" href="{{ url('auth/login') }}">Login</a>
                            <div class="links-tab"></div>
                            <a class="menu-link menu-link-register" href="{{ url('auth/register') }}">Sign Up</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- menu --}}
    <div class="main-header">
        <div class="container">
            <div class="row">

                <!-- Logo -->
                <div class="col-xs-9 col-sm-5 col-md-4 col-lg-3">
                    <a class="header-logo" rel="home" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Best Speech To Text Service" title="Speech To Text">
                    </a>
                </div>

                <!-- Collapse button -->
                <div class="col-xs-3 col-sm-7 visible-xs-block visible-sm-block">
                    <button type="button" class="menu-toggle" id="menuToggle">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!-- The collapsing menu -->
                <div class="col-xs-12 col-md-8 col-lg-9">
                    <div class="menu" id="menuCollapse">
                        <ul>
                            <li><a class="{{ (Request::url() == route('contacts')) ? 'active' : '' }}" href="{{ route('contacts') }}">About us</a></li>
                            <li><a class="{{ (Request::url() == route('services')) ? 'active' : '' }}" href="{{ route('services') }}">Services</a></li>
                            <li><a class="{{ (Request::url() == route('reviews')) ? 'active' : '' }}" href="{{ route('reviews') }}">Feedback</a></li>
                            <li><a class="{{ (Request::url() == route('pricing')) ? 'active' : '' }}" href="{{ route('pricing') }}">Pricing</a></li>
                            <li><a class="{{ (Request::url() == route('faq')) ? 'active' : '' }}" href="{{ route('faq') }}">FAQ</a></li>
                            <li><a class="{{ (Request::url() == route('blog')) ? 'active' : '' }}" href="{{ route('blog') }}">Blog</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- Breadcrumb --}}
@unless (Route::is('homepage'))
    <div class="site-breadcrumb">
        <div class="container">
            {{-- Homepage link --}}
            <span><a href="{{ route('homepage') }}">Speech To Text</a></span>

            {{-- Intermediate links --}}
            @yield('breadcrumb', '')

            {{-- Current page link --}}
            <span><a href="{{ Request::url() }}">@yield('title')</a></span>
        </div>
    </div>
@endunless