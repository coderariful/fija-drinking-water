@extends('admin.layouts.master')

@section('title', config('app.name', 'Fija Drinking Water').' | '.$title)

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('user.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.customer.index') }}">{{__('All Customer')}}</a>
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
                <form class="" action="{{route('admin.customer.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('admin.customer.form')

                        <div class="row">
                            <div class="col-md-10 col-lg-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="due_amount" class="card-title font-weight-bold">{{__('Due Amount:')}}</label>
                                            <input type="number" step="any" name="due_amount" id="due_amount" class="form-control" placeholder="{{__('Due Amount')}}"   value="{{old('due_amount')}}">
                                            @error('due_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jar_stock" class="card-title font-weight-bold">{{__('Jar Stock:')}}</label>
                                            <input type="number" name="jar_stock" id="jar_stock" class="form-control" placeholder="{{__('Jar Stock')}}"   value="{{old('jar_stock')}}">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="send_sms" class="card-title font-weight-bold">{{__('Send SMS:')}}</label>
                                            <select class="form-control" name="send_sms" id="send_sms">
                                                <option class="text-success" value="{{YES}}" @selected(old('send_sms')==YES)>{{__('Yes')}}</option>
                                                <option class="text-danger" value="{{NO}}" @selected(is_null(old('send_sms')) ? null : intval(old('send_sms')) ===NO)>{{__('No')}}</option>
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
    <script>
        // $('#employee').SumoSelect();
    </script>
@endpush
