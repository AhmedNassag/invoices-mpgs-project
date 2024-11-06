@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">{{ __('login.welcome_back') }}</h1>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="user">
                            
                            @csrf
                            <div class="form-group">
                                <input id="demoemail" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" placeholder="Enter Email Address" name="email" value="{{ old('email') }}"/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input id="demopassword" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="Enter Password" name="password" />
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('login.remember_me') }}
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                {{ __('login.login') }}
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
                            <a class="small" href="{{ route('register') }}">{{ __('login.create_an_account') }}</a>
                        </div>
                        @if(config('green.demo'))
                            <div class="text-center">
                                <hr>
                                <h5 class="text-danger">{{ __('Demo Login Panel') }}</h5>
                                <hr>
                                <button type="submit" id="demoadmin" class="btn btn-success">
                                    {{ __('Admin') }}
                                </button>
                                <button type="submit" id="demomoderator" class="btn btn-danger">
                                    {{ __('Moderator') }}
                                </button>
                                <button type="submit" id="democustomer" class="btn btn-success">
                                    {{ __('Customer') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection