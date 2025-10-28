@extends('admin.layouts.master')

@section('title', config('app.name', 'laravel').' | '.$title)

@push('css')
    <style>
        .form-control {
            padding-right:0!important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{$title}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>

    <livewire:admin.customer-index :title="$title" :status="$status??CUSTOMER_APPROVED"/>
@endsection

@push('scripts')
@endpush
