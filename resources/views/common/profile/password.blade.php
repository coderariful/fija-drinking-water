@extends("$layout.layouts.master")

@section('title', 'Profile Update')

@push('css')

@endpush

@section('content')
<div class="row">
    <div class="col">
        <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
            <a class="breadcrumb-item text-white" href="{{route('admin.dashboard')}}">{{ __('Home') }}</a>
            <span class="breadcrumb-item active">{{ __('Profile Info') }}</span>
            <span class="breadcrumb-info" id="time"></span>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-dark bg-dark py-1">

            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="h5">{{ __('Update Password') }}</div>
                        <p class="">{{ __('Ensure your account is using a long, random password t stay secure.') }}</p>
                        <img src="{{ asset('backend/assets/img/logo/logo.png') }}"
                             class="img-fluid img-thumbnail sidebar-profile-img" />
                    </div>
                    <div class="col-md-8">

                        <form action="{{route('profile.password.update')}}" method="POST" class="wma-form">
                            @csrf
                            <div class="">
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group input-group-lg">
                                            <label for="exampleInputEmail1">{{ __('Old Password') }}:</label>
                                            <input type="password" class="form-control" name="current_password" id="current_password" aria-describedby="emailHelp" >
                                            @if ($errors->has('current_password'))
                                                <p class="form-text text-danger">{{ $errors->first('current_password') }}</p>
                                            @endif
                                          </div>
                                          <div class="form-group input-group-lg">
                                            <label for="exampleInputEmail1">{{ __('New Password') }}:</label>
                                            <input type="password" class="form-control" name="password" id="password" aria-describedby="emailHelp" >
                                            @if ($errors->has('password'))
                                                <p class="form-text text-danger">{{ $errors->first('password') }}</p>
                                            @endif
                                          </div>
                                          <div class="form-group input-group-lg">
                                            <label for="exampleInputEmail1">{{ __('Confirm Password') }}:</label>
                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" aria-describedby="emailHelp" >
                                            @if ($errors->has('password_confirmation'))
                                                <p class="form-text text-danger">{{ $errors->first('password_confirmation') }}</p>
                                            @endif
                                          </div>

                                        <div class="form-group">
                                            <input type="checkbox" name="keep_me_login" id="keepMeLogin">
                                            <label for="keepMeLogin">{{ __('Keep Me Login') }}</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="float-right">
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                    <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{ __('Update Password') }}</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


@push('scripts')
    @include('admin.includes.message')

@endpush
