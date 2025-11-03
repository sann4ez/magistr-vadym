<!-- Navbar -->
<nav class="main-header navbar navbar-expand text-sm {{config('lte3.view.dark_mode') ? 'dark-mode' : 'navbar-white navbar-light'}}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        @can('dev')
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                   class="nav-link dropdown-toggle"><i class="far fa-clock"></i></a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow"
                    style="left: 0px; right: inherit;">
                    <li><a href="#" class="dropdown-item">UTC: {{ now()->timezone(config('app.timezone')) }} </a></li>
                    @if(config('app.timezone_client'))
                        <li><a href="#" class="dropdown-item">{{ config('app.timezone_client') }}: {{ now()->timezone(config('app.timezone_client')) }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endcan

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        @auth
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ auth()->user()->getAvatar() }}" class="user-image img-circle elevation-2"
                         alt="User Image">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2"
                             alt="User Image">

                        <p>
                            Привіт {{ Lte3::user('name') }}!
                            <small>Created {{ Lte3::user('created_at') }}</small>
                        </p>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="/admin/profile" class="btn btn-default btn-flat">Профіль</a>
                        <a href="/logout" class="btn btn-default btn-flat float-right js-click-submit"
                           data-confirm="Logout?">Вихід</a>
                    </li>
                </ul>
            </li>
        @endauth

    </ul>
</nav>
