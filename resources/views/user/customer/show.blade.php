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
                   href="{{ route('user.all-customer') }}">{{__('All Employee')}}</a>
                <span class="breadcrumb-item active">{{$title?$title:''}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title">{{$title?$title:''}}</h6>
                </div>
                <form class="" action="{{route('user.customer.update',$value->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Name:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="name" id="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Input here')}}"   value="{{$value?$value->name:''}}">
                                    <br>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Phone:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="phone" id="phone" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Phone number')}}" value="{{$value?$value->phone:''}}">
                                    <br>
                                    @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Address:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="text" name="address" id="address" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Address')}}" value="{{$value?$value->address:''}}">
                                    <br>
                                    @if ($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Issue Date:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="date" name="issue_date" id="issue_date" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Issue Date')}}" value="{{$value?$value->issue_date:''}}">
                                    <br>
                                    @if ($errors->has('issue_date'))
                                        <span class="text-danger">{{ $errors->first('issue_date') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Jar Quantity:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="number" name="jar_quantity" id="jar_quantity" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__(' Jar Quantity')}}" value="{{$value?$value->jar_quantity:''}}">
                                    <br>
                                    @if ($errors->has('jar_quantity'))
                                        <span class="text-danger">{{ $errors->first('jar_quantity') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">

                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Dispenser:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <select class="form-control form-control-lg" name="despenser">
                                        <option value="{{$value?$value->despenser:''}}" selected>{{$value?$value->despenser:''}}</option>
                                        <option class="text-success" value="One Tap Dispenser">{{__('One Tap Dispenser')}}</option>
                                        <option class="text-success" value="Two Tap Dispenser">{{__('Two Tap Dispenser')}}</option>
                                        <option class="text-success" value="Four Tap Dispenser">{{__('Four Tap Dispenser')}}</option>

                                    </select>
                                    <br>
                                    @if ($errors->has('despenser'))
                                        <span class="text-danger">{{ $errors->first('despenser') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Payment Cash:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="number" name="cash_payment" id="cash_payment" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Payment Cash')}}" value="{{$value?$value->cash_payment:''}}">
                                    <br>
                                    @if ($errors->has('cash_payment'))
                                        <span class="text-danger">{{ $errors->first('cash_payment') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Due Payment:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="number" name="due_payment" id="due_payment" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                           placeholder="{{__('Due Payment')}}" value="{{$value?$value->due_payment:''}}">
                                    <br>
                                    @if ($errors->has('due_payment'))
                                        <span class="text-danger">{{ $errors->first('due_payment') }}</span>
                                    @endif
                                </div>
                                <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Status:')}}</label> </p>
                                <div class="input-group input-group-lg mb-3">
                                    <select class="form-control form-control-lg" name="status">
                                        <option value="{{$value?$value->status:''}}" selected>@if($value->status == 1){{__('Active')}}@else{{__('DeActive')}}@endif</option>
                                        <option class="text-success" value="1">{{__('Active')}}</option>
                                        <option class="text-danger" value="0">{{__('Deactive')}}</option>

                                    </select>
                                    <br>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
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
