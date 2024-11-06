@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-users"></i> {{ __('user.user') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.user.index') }}" class="text-white"><i class="fas fa-users fa-sm text-white-50"></i> {{ __('user.user') }} / {{ __('Edit') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card">
                    <div class="text-center p-2 mb-0 mt-3">
                        <img alt="image" src="{{ $user->image }}" class="rounded-circle img-profile" />
                        <ul class="profile-list">
                          <li><b>{{ $user->name }}</b></li>
                          <li>{{ $user->designation }}</li>
                          <li>{{ $user->email }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <form method="POST" action="<?=route('admin.user.update', $user)?>" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4 class="mb-0">{{ __('Edit User') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"/>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Designation') }}</label>
                                    <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $user->designation) }}"/>
                                    @error('designation')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Email') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"/>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}"/>
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Date of Birth') }}</label>
                                    <input type="text" name="date_of_birth" class="form-control datepicker @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->custom_date_of_birth) }}"/>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Joining Date') }}</label>
                                    <input type="text" name="joining_date" class="form-control datepicker @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', $user->custom_joining_date) }}"/>
                                    @error('joining_date')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="address" id="address" cols="30" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{ __('Photo') }}</label> <span class="text-danger">*</span>
                                    <div class="custom-file">
                                        <input name="image" type="file" class="custom-file-input upload-file-input @error('image') is-invalid @enderror" id="userphoto">
                                        <label class="custom-file-label" for="userphoto">{{ __('Choose file...') }}</label>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    <img class="img-thumbnail image-width mt-2 mb-2 setting-logo" src="{{ $user->image }}" alt="{{ setting('image') }} Logo">
                                </div>

                                <hr class="w-100">
                                <div class="form-group col-md-4">
                                    <label>{{ __('user.role') }}</label> <span class="text-danger">*</span>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="0">{{ __('Please Select') }}</option>
                                        @if(!blank($roles))
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ (old('role', $user->role_id) == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Username') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}"/>
                                    @error('username')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>{{ __('Password') }}</label>
                                    <input type="text" name="password" class="form-control @error('password') is-invalid @enderror"/>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('header_css')
    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush

@push('footer_scripts')
    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
@endpush