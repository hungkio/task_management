@extends('admin.layouts.base')

@section('title', __('Login'))
@push('css')
  <style>
    .page-content{
        background-image: url('{{ asset('images/background.jpg') }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
    .login-form{
        max-width: 600px;
        width: 100%;
        height: 80%;
    }
    .login-form .card{
        background: linear-gradient(to bottom, rgba(103,28,12, 0.8) 7%, rgba(189,77,41,0.6) 30%, rgba(247,139,74,0.4) 80%);
        border: solid 2px #8e6efa;
    }
    .logo{
        max-width: 150px;
        width: 100%;
        margin: 0 auto;
        display: flex;
    }
    .logo img{
        width: 100%;
        object-fit: cover;
    }
    .card-body{
        padding: 0 75px;
    }
    .login-form input[type="text"],
    .login-form input[type="password"]{
        background-color: rgba(255, 255, 255, 0.5);
        border: none;
        height: 60px;
        border-radius: 6px;
        font-size: 20px;
        font-weight: 700;
    }
    .login-form i{
        color: #000 !important;
        font-size: 20px;
    }
    .login-form input::placeholder{
        color: #fff;
        font-weight: 700;
        font-size: 20px;
    }
    .form-control-feedback{
        top: 13px;
    }
    .login-text{
        font-size: 20px;
        font-weight: 1000;
        color: #fff;
        text-transform: uppercase;
    }
    .btn-login {
        border: solid 2px #fff;
        border-radius: 8px;
        background-image: linear-gradient(to right, red , yellow);
        transition: 0.5s all;
        background-size: 200% 100%;
        background-position: 50% 0;
        width: 100%;
        padding: 10px 0;
        outline: none;
    }

    .btn-login:hover {
        background-position: 100% 100%;
        transition: 0.5s all;
    }
    .btn-login:focus{
        outline: none;
    }
  </style>
@endpush
@section('content')
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center pt-0">

                <!-- Login card -->
                <form class="login-form" action="{{ route('admin.login') }}" method="POST" id="login-form" data-block>
                    @csrf
                    <div class="card mb-0 h-100">
                        <div class="card-body h-100">
                            <div class="logo">
                                <img src="{{ asset(('images/logo-login.png')) }}" alt="">
                            </div>
                            <div class="text-center mb-5 mt-3">
                                <h3 class="mb-0 login-text">{{ __('Login') }}</h3>
                            </div>

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input value="" id="email" name="email" type="text" class="form-control @error('email') border-danger @enderror" placeholder="{{ __('Username') }}">
                                <div class="form-control-feedback">
                                    <i class="far fa-user text-muted"></i>
                                </div>
                                @error('email')
                                <span class="form-text text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input id="password" name="password" type="password" class="form-control @error('password') border-danger @enderror" placeholder="{{ __('Password') }}">
                                <div class="form-control-feedback">
                                    <i class="far fa-lock text-muted"></i>
                                </div>
                                @error('password')
                                <span class="form-text text-danger">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="login-text btn-login">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /login card -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
@stop
