@extends('admin.layouts.master')

@section('title', $title)

@push('css')

@endpush

@section('content')
<div class="row">
    <div class="col">
        <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
            <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{ __('Home') }}</a>
            <span class="breadcrumb-item active">{{ $title }}</span>
            <span class="breadcrumb-info" id="time"></span>
        </nav>
    </div>
</div>


<div class="row dashboard-cards mx-2">
    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.customer.index')}}">
            <div class="card card-dark bg-primary">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Customers') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_customer ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.customer.index', ['day' => 'today'])}}">
            <div class="card card-dark bg-success">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('New Customer Today') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $new_customer ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.customer.pending')}}">
            <div class="card card-dark bg-danger">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Pending Customer') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $pending_customer }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.employee.index')}}">
            <div class="card card-dark bg-dark">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Employees') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_employee ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.customer.index')}}">
            <div class="card card-dark bg-secondary">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Jar Stock') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_jar_stock ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.sales.index', ['day' => 'today'])}}">
            <div class="card card-dark bg-warning">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Sell Today') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_sell_today }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.payments.index', ['day' => 'today'])}}">
            <div class="card card-dark bg-info">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Collection Today') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_collect_today }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.customer.index', ['day' => 'today'])}}">
            <div class="card card-dark bg-indigo">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Due Today') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ roundFormat($total_due_today) }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.sales.index', ['day' => 'today', 'product' => 'jar'])}}">
            <div class="card card-dark bg-blue">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Jar Sell Today') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $jar_sale_today }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.sales.index', ['range' => 'month', 'product' => 'jar'])}}">
            <div class="card card-dark bg-deep-purple">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Jar Sell This Month') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $jar_sale_this_month }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-sm-6">
        <a href="{{route('admin.sales.index', ['range' => 'month', 'product' => 'jar'])}}">
            <div class="card card-dark bg-deep-purple">
                <div class="card-body d-flex">
                    <i class="display-2 fa fa-users"></i>
                    <div class="ml-auto align-self-center text-right">
                        <span class="card-title mb-1">{{ __('Total Due') }}</span>
                        <h3 class="card-title font-montserrat mb-0">{{ $total_due }}</h3>
                    </div>
                </div>
            </div>
        </a>
    </div>

    @foreach($dispenser as $dispense)
        <div class="col-xl-3 col-sm-6">
            <a href="{{route('admin.sales.index', ['product' => $dispense->id])}}">
                <div class="card card-dark {{fake()->randomElement(['bg-light-blue', 'bg-light-green'])}}">
                    <div class="card-body d-flex">
                        <i class="display-2 fa fa-users"></i>
                        <div class="ml-auto align-self-center text-right">
                            <span class="card-title mb-1">{{ $dispense->name }}</span>
                            <h3 class="card-title font-montserrat mb-0">{{ $dispense->sales_count  }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-12">
        <livewire:dashboard-customer-search/>
    </div>
</div>


<div class="row">
    <div class="col-lg-7">
        <div class="card card-dark card-justify bg-dark">

            <div class="card-header">
                <h6 class="card-title">{{ __('activity') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between pt-1 pb-3">
                    <div>
                        <span class="card-title mb-0 text-capitalize ml-2">{{ __('Last 12 Month Statement') }}</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="badge badge-legend badge-danger ml-2"></span>
                        <span class="card-title  mb-0 text-capitalize ml-2">{{ __('Customer Increase') }}</span>
                    </div>
                </div>
                <canvas class="maxh-310px" data-style="dark" id="chart-line-activity"></canvas>
            </div>


        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-justify">
            <div id="calendar_dark"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('backend/assets/js/tables-datatable.js')}}"></script>
@include('admin.includes.dashboard-js')
@endpush
