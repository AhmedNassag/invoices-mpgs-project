@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-8 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <h1 class="h4 text-gray-900">{{ __('Create An Account!') }}</h1>
                            <hr>
                        </div>
                        <form method="POST" action="{{ route('register') }}" class="user">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"/>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Email') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"/>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Phone') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"/>
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Address') }}</label> <span class="text-danger">*</span>
                                    <textarea name="address" id="address" cols="30" rows="1" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <hr class="w-100">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Username') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"/>
                                    @error('username')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Password') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="password" class="form-control @error('password') is-invalid @enderror"/>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                {{ __('Register Account') }}
                            </button>
                        </form>
                        <hr />
                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">
                                    {{ __('login.forget_your_password') }}
                                </a>
                            @endif
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