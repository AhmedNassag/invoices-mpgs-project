@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900">{{ __('Forgot Your Password?') }}</h1>
                            <p class="mb-4">{{ __('We get it, stuff happens. Just enter your email address below and we\'ll send you a link to reset your password!') }}</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('password.email') }}" class="user">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" placeholder="Enter Email Address" name="email" value="{{ old('email') }}"/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                {{ __('Reset Password') }}
                            </button>
                        </form>
                        <hr />
                        <div class="text-center">
                            <a class="small" href="{{ route('register') }}">
                                {{ __('Create an Account!') }}
                            </a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">{{ __('Already have an account? Login!') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
