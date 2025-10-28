@extends('user.layouts.master')

@section('title', config('app.name', 'laravel').' | '.$title)

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('user.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                   href="{{ route('user.customer.index') }}">{{__('All Customers')}}</a>
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
                <form class="" action="{{route('user.customer.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('user.customer.form')

                        <div class="row">
                            <div class="col-md-10 col-lg-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="due_amount" class="card-title font-weight-bold">{{__('Due Amount:')}}</label>
                                            <input type="text" name="due_amount" id="due_amount" class="form-control" placeholder="{{__('Due Amount')}}"   value="{{old('due_amount')}}">
                                            @error('due_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jar_stock" class="card-title font-weight-bold">{{__('Jar Stock:')}}</label>
                                            <input type="text" name="jar_stock" id="jar_stock" class="form-control" placeholder="{{__('Jar Stock')}}"   value="{{old('jar_stock')}}">
                                            @error('jar_stock')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dispenser" class="card-title font-weight-bold">{{__('Dispenser:')}}</label>
                                            <select class="form-control" name="dispenser" id="dispenser">
                                                <option value="" @selected(old('dispenser')=='')>{{__('None')}}</option>
                                                @foreach($dispensers as $dispenser)
                                                    <option value="{{$dispenser->id}}" @selected(old('dispenser')==$dispenser->id)>{{$dispenser->name}} -- {{$dispenser->price}} Tk.</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('user.customer.internal-assets.js.team-member')
    @include('user.includes.message')
@endpush

