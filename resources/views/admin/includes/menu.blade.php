<ul class="sidebar-nav">
    <!-- NAV ITEM -->
    <li class="nav-item {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
        <a href="{{route('admin.dashboard')}}" class="nav-link"><i class="material-icons">dashboard</i><span class="link-text">{{__('Dashboard')}}</span></a>
    </li>
    <li class="nav-item {{ (Route::is("admin.customer.inactive")) ? 'active' : '' }}">
        <a href="{{route('admin.customer.inactive')}}" class="nav-link">
            <i class="material-icons">people</i>
            <span class="link-text">{{__('Inactive Customers')}}</span>
        </a>
    </li>
    <li class="nav-item has-dropdown {{ Route::is("admin.customer*") ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link">
            <i class="material-icons">people</i>
            <span class="link-text">{{ __('Customers') }}</span>
            <span class="badge badge-md"><i class="material-icons fs-12pt">chevron_right</i></span>
        </a>

        <ul class="dropdown-list" style="display:{{ Route::is("admin.customer*") ? 'block' : 'none' }}">
            <li class="{{ Route::is("admin.customer.create") ? 'active' : '' }}">
                <a href="{{route('admin.customer.create')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Add Customer')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.customer.index", "admin.customer.edit"]) ? 'active' : '' }}">
                <a href="{{route('admin.customer.index')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('All Customers')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.customer.pending"]) ? 'active' : '' }}">
                <a href="{{route('admin.customer.pending')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Pending Customers')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.customer.rejected"]) ? 'active' : '' }}">
                <a href="{{route('admin.customer.rejected')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Rejected Customers')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.customer.request"]) ? 'active' : '' }}">
                <a href="{{route('admin.customer.request')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Edit Request')}}</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ Route::is('admin.sales*') ? 'active' : '' }}">
        <a href="{{route('admin.sales.index')}}" class="nav-link">
            <i class="material-icons">business_center</i><span class="link-text">{{__('Sales')}}</span>
        </a>
    </li>
    <li class="nav-item {{ Route::is('admin.payments*') ? 'active' : '' }}">
        <a href="{{route('admin.payments.index')}}" class="nav-link">
            <i class="material-icons">payment</i><span class="link-text">{{__('Payments')}}</span>
        </a>
    </li>
    <li class="nav-item has-dropdown {{ Route::is("admin.product*") ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link">
            <i class="material-icons">archive</i>
            <span class="link-text">{{ __('Products') }}</span>
            <span class="badge badge-md"><i class="material-icons fs-12pt">chevron_right</i></span>
        </a>

        <ul class="dropdown-list" style="display:{{ Route::is("admin.product*") ? 'block' : 'none' }}">
            <li class="{{ Route::is("admin.product.create") ? 'active' : '' }}">
                <a href="{{route('admin.product.create')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Add Product')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.product.index", "admin.product.edit"]) ? 'active' : '' }}">
                <a href="{{route('admin.product.index')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('All Products')}}</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item has-dropdown  {{ Route::is("admin.employee*") ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link">
            <i class="material-icons">people</i>
            <span class="link-text">{{ __('Employee') }}</span>
            <span class="badge badge-md"><i class="material-icons fs-12pt">chevron_right</i></span>
        </a>
        <ul class="dropdown-list" style="display:{{ Route::is("admin.employee*")  ? 'block' : 'none' }}">
            <li class="{{ Route::is("admin.employee.create") ? 'active' : '' }}">
                <a href="{{route('admin.employee.create')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Add New Employee')}}</span>
                </a>
            </li>
            <li class="{{ Route::is(["admin.employee.index", "admin.employee.edit"]) ? 'active' : '' }}">
                <a href="{{route('admin.employee.index')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('All Employees')}}</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ Route::is('admin.sms-template*') ? 'active' : '' }}">
        <a href="{{route('admin.sms-template')}}" class="nav-link"><i class="material-icons">mail</i><span class="link-text">{{__('SMS Template')}}</span></a>
    </li>
    <li class="nav-item has-dropdown {{ Route::is("admin.settings*") ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link">
            <i class="material-icons">build</i>
            <span class="link-text">{{ __('Settings') }}</span>
            <span class="badge badge-md"><i class="material-icons fs-12pt">chevron_right</i></span>
        </a>
        <ul class="dropdown-list" style="display:{{ Route::is("admin.settings*") ? 'block' : 'none' }}">
            <li class=" {{ Route::is("admin.settings.general") ? 'active' : '' }}">
                <a href="{{route('admin.settings.general')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('General Settings')}}</span>
                </a>
            </li>
            <li class=" {{ Route::is("admin.settings.logo-favicon") ? 'active' : '' }}">
                <a href="{{route('admin.settings.logo-favicon')}}" class="nav-link"> <i class="material-icons">chevron_right</i>
                    <span class="link-text">{{__('Logo Favicon')}}</span>
                </a>
            </li>
        </ul>
    </li>
     {{--<li class="nav-item {{ (request()->is('admin/money/details')) ? 'active' : '' }}">
         <a href="{{route('admin.money.details')}}" class="nav-link"><i class="material-icons">credit_card</i>
             <span class="link-text">{{__('Money Details')}}
             </span>
         </a>
     </li> --}}
    </ul>
