@extends('admin.layouts.master')

@section('title', 'General Settings')

@push('css')

@endpush

@section('content')
<div class="row">
    <div class="col">
        <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
            <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{ __('Home') }}</a>
            <span class="breadcrumb-item active">{{$title}}</span>
            <span class="breadcrumb-info" id="time"></span>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header">
                <h6 class="card-title">{{ __('Basic') }}</h6>
            </div>
            <div class="card-body ">

                <form action="{{route('admin.settings.general.store')}}" method="POST" enctype="multipart/form-data" class="wma-form">
                    <div class="card-body ">
                        @csrf
                        <p class="mb-1">{{__('Site Name')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="site_name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Site Name')}}" value="{{$site_name??''}}">
                            <br>
                            @if ($errors->has('site_name'))
                                <span class="text-danger">{{ $errors->first('site_name') }}</span>
                            @endif
                        </div>

                        <p class="mb-1">{{__('Og Meta Title')}}: </p>
                        <div class="input-group input-group-lg mb-3">
                            <input type="text" name="og_meta_title" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                                   placeholder="{{__('Og Meta Title')}}" value="{{$og_meta_title??''}}">
                        </div>

                        <p class="mb-1">{{__('Og Meta Description')}}: <code>{{__('maximum 50 word')}}</code></p>
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="og_meta_description" aria-label="With textarea"
                                      rows="4">{{$og_meta_description??''}}</textarea>
                        </div>

                        <p class="mb-1">{{__('Og Meta Image')}}: <code>{{__('expected size is 32x32px')}}</code></p>
                        <div class="form-row">
                            <div class="col-md-10 col-sm-12">
                                <div class="form-group">
                                    <div role="button" class="btn btn-primary mr-2">
                                        <input type="file" title='Click to add Files' name="og_meta_image" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2  d-md-block  d-sm-none">
                                <div class="img-favicon">
                                    <img src="{{$og_meta_image??''}}" alt="Og Meta Image" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="send_sms" class="card-title font-weight-bold">{{__('Send Daily/Monthly SMS:')}}</label>
                                    <select class="form-control" name="send_sms" id="send_sms">
                                        <option class="text-success" value="{{YES}}" @selected(old('send_sms', $send_sms??null)==YES)>{{__('Yes')}}</option>
                                        <option class="text-danger" value="{{NO}}" @selected(is_null(old('send_sms')) ? null : intval(old('send_sms')) ===NO)>{{__('No')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="wizard-action text-left">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit form')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection


@push('scripts')
@include('admin.includes.message')
@endpush
