@extends('user.layouts.master')

@section('title', config('app.name', 'laravel').' | '.$title)

@push('css')

@endpush

@section('content')
<div class="row">
    <div class="col">
        <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
            <a class="breadcrumb-item text-white" href="{{route('user.dashboard')}}">{{ __('Home') }}</a>
            <span class="breadcrumb-item active">{{ $title?$title:'' }}</span>
            <span class="breadcrumb-info" id="time"></span>
        </nav>
    </div>
</div>


<div class="row">
    <div class="col-xl-4 col-sm-6">
        <div class="card card-dark bg-dark">

            <div class="card-body d-flex">
                <i class="display-2 material-icons">attach_money</i>
                <div class="ml-auto align-self-center text-right">
                    <span class="card-title mb-1">{{ __('Total Money') }}</span>
                    <h3 class="card-title font-montserrat mb-0">{{$totalMoney?$totalMoney:''}} {{__('Taka')}}</h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6">
        <div class="card card-dark bg-success">

            <div class="card-body d-flex">
                <i class="display-2 material-icons">attach_money</i>
                <div class="ml-auto align-self-center text-right">
                    <span class="card-title mb-1">{{ __('Total Collect Money') }}</span>
                    <h3 class="card-title font-montserrat mb-0">{{$totalCollectMoney?$totalCollectMoney:''}} {{__('Taka')}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6">
        <div class="card card-dark bg-danger">

            <div class="card-body d-flex">
                <i class="display-2 material-icons">money_off</i>
                <div class="ml-auto align-self-center text-right">
                    <span class="card-title mb-1">{{ __('Total Due Money') }}</span>
                    <h3 class="card-title font-montserrat mb-0">{{$totalDueMoney?$totalDueMoney:''}} {{__('Taka')}}</h3>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@endpush
