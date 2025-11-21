<ul class="sidebar-nav">
    <li class="nav-item has-dropdown {{ request()->is('user/add/new-customer*') || request()->is('user/all/customers*')  ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link">
            <i class="material-icons">people</i>
            <span class="link-text">{{ __('Customers') }}</span>
            <span class="badge badge-md"><i class="material-icons fs-12pt">chevron_right</i></span>
        </a>
        <ul class="dropdown-list" style="display:{{ Route::is('user.customer*') ? 'block' : 'none' }}">

            <!-- NAV ITEM -->
            <li class="nav-item {{ (request()->is('user/dashboard')) ? 'active' : '' }}">
                <a href="{{route('user.dashboard')}}" class="nav-link"><i class="material-icons">dashboard</i><span class="link-text">{{__('Dashboard')}}</span></a>
            </li>

            <li class="{{ Route::is('user.customer.create') ? 'active' : '' }}">
                <a href="{{route('user.customer.create')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Add New Customer')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(['user.customer.index', 'user.customer.edit']) ? 'active' : '' }}">
                <a href="{{route('user.customer.index')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('All Customers')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(['user.customer.index', 'user.customer.edit']) ? 'active' : '' }}">
                <a href="{{route('user.customer.index', ['status' => 'pending'])}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Pending Customers')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(['user.customer.index', 'user.customer.edit']) ? 'active' : '' }}">
                <a href="{{route('user.customer.index', ['status' => 'rejected'])}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Rejected Customers')}}</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item {{ Route::is('user.sales*') ? 'active' : '' }}">
        <a href="{{route('user.sales.index')}}" class="nav-link">
            <i class="material-icons">business_center</i><span class="link-text">{{__('Sales')}}</span>
        </a>
    </li>
    <li class="nav-item {{ Route::is('user.payments*') ? 'active' : '' }}">
        <a href="{{route('user.payments.index')}}" class="nav-link">
            <i class="material-icons">payment</i><span class="link-text">{{__('Payments')}}</span>
        </a>
    </li>

    <li class="nav-item mt-auto">
        <form action="{{ route('stop-impersonate') }}" method="POST">
            @csrf
            <button class="dropdown-item text-warning nav-link" type="submit" >
                <i class="material-icons">backspace</i>
                <span class="link-text">
                    {{ __("Back to Admin") }}<br>{{ __('Stop Impersonation') }}
                </span>
            </button>
        </form>
    </li>
</ul>
