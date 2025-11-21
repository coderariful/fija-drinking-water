<div id="wrapper-header">
    <!-- NAVABR -->
    <nav class="navbar navbar-expand navbar-dark navbar-danger bg-dark">
        <!-- NAVABR NAV - LEFT -->
        <ul class="navbar-nav">
            <!-- NAV ITEM - SIDEBARTOGGLE -->
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);" data-toggle="class" data-target="#wrapper" toggle-class="toggled">
                    <i data-toggle="switch" data-iconFirst="menu" data-iconSecond="close" class="material-icons">menu</i>
                </a>
            </li>
        </ul>

        <!-- NAVABR NAV - RIGHT -->
        <ul class="navbar-nav ml-auto">
            <!-- NAV ITEM - LANG -->
            <!-- NAV ITEM - NOTIFICATIONS -->

            <!-- NAV ITEM - PARAMETRES -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle no-caret d-flex align-items-center" href="javascript:void(0);" id="settings" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('upload/profilePhoto/'.Auth::user()->profile_photo_path) : asset('backend/assets/img/logo/logo.png') }}"
                         class="rounded-circle roundedCircleAvatar"/>
                    <span class="d-sm-inline-block d-none pl-2 pr-1">{{  Auth::user()->name }}</span>
                    <i class="d-sm-inline-block d-none material-icons icon-xs">keyboard_arrow_down</i>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('profile')}}"><i class="material-icons">person</i> {{ __('Update Profile') }}</a>
                    <a class="dropdown-item" href="{{route('profile.password.change')}}"><i class="material-icons">settings</i> {{__('Change Password')}}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{route('logout')}}" id="logoutBtn">
                        <i class="material-icons">power_settings_new</i> {{ __('Logout') }}
                        <form id="logout-form" class="d-none" action="{{route('logout')}}" method="POST">
                            @csrf
                        </form>
                    </a>
                    @if(session()->has('impersonated_by'))
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('stop-impersonate') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-warning" type="submit" >
                                <i class="material-icons">backspace</i>{{ __("Back to Admin") }} - {{ __('Stop Impersonation') }}
                            </button>
                        </form>
                    @endif
                </div>
            </li>
        </ul>

    </nav>
</div>
