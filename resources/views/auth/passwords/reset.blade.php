@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900">
                                {{ __('Reset Password') }}
                            </h1>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}" class="user">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" placeholder="Enter Email Address" name="email" value="{{ old('email', request('email')) }}"/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="Password" name="password" value="{{ old('password') }}"/>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
                                @error('password_confirmation')
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
