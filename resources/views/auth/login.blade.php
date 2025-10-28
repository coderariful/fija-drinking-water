@extends('layouts.guest')

@section('title', 'Login')

@push('css')

@endpush


@section('content')
    <div class="login">
        <div class="login__content">

            <div class="login__img">
                <img src="{{ asset('backend/assets/img/logo/logo.png') }}" alt="">
            </div>

            <div class="login__forms">
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{route('login')}}" class="login__registre" id="login-in">
                    @csrf
                    <h1 class="login__title">{{ __('Sign In') }}</h1>

                    <div class="login__box">
                        <i class="fa fa-user login__icon" aria-hidden="true"></i>
                        <input type="text" placeholder="{{ __('Phone') }}"
                               class="login__input @error('phone') is-invalid @enderror" name="phone" id="phone"
                               value="{{ old('phone') }}" required>
                    </div>
                    <div class="login__box">
                        <i class="fa fa-lock login__icon" aria-hidden="true"></i>
                        <input type="password" name="password" autocomplete="current-password"
                               placeholder="{{ __('Password') }}"
                               class="login__input @error('password') is-invalid @enderror" required>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-left mt-2">
                            <input type="checkbox"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   id="remember_me" name="remember">
                            <span class="ml-1 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </div>
                        <div class="col">
                            <a href="{{ route('password.request') }}" class="login__forgot">{{ __('Forgot your password?') }}</a>
                        </div>
                    </div>
                    <button type="submit" class="login__button" id="login-button">{{ __('Log in') }}</button>

                    {{-- <p class="mx-auto">{{ __('OR') }}</p>
                     <div>
                         <span class="login__account">{{ __('Don\'t have an Account ?') }}</span>
                         <span class="login__signin" id="sign-up">{{ __('Sign Up') }}</span>
                     </div> --}}
                </form>

                {{-- <form method="POST" action="{{route('register')}}" class="login__create none" id="login-up">
                    @csrf
                    <h1 class="login__title">{{ __('Create Account') }}</h1>
                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="name" placeholder="Username" class="login__input">
                    </div>
                    <div class="login__box">
                        <i class='bx bx-at login__icon'></i>
                        <input type="text" name="phone" placeholder="Phone" class="login__input">
                    </div>
                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Password" class="login__input">
                    </div>
                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password"
                               class="login__input">
                    </div>
                    <button type="submit" class="login__button">{{ __('Sign Up') }}</button>
                    <div>
                        <span class="login__account">{{ __('Already have an Account ?') }}</span>
                        <span class="login__signup" id="sign-in">{{ __('Sign In') }}</span>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('frontend/js/custom-js/sign-in.js')}}" defer></script>
@endpush
