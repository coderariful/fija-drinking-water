@extends('admin.layouts.master')

@section('title', config('app.name', 'laravel').' | '.$title)

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.employee.index') }}">{{__('All Employee')}}</a>
                <span class="breadcrumb-item active">{{$title}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title">{{$title}}</h6>
                </div>
                <form class="" action="{{route('admin.employee.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('admin.employee.form')
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.employee.internal-assets.js.team-member')
    @include('admin.includes.message')
@endpush
