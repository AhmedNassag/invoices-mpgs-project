@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-calculator"></i> {{ __('Unit') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.unit.index') }}" class="text-white"><i class="fas fa-calculator fa-sm text-white-50"></i> {{ __('Unit') }}</a> /
                <a class="text-white">{{ __('Add') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-6 col-md-6">
                <form method="POST" action="<?=route('admin.unit.store')?>">
                    @csrf
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('Name') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"/>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Status') }}</label> <span class="text-danger">*</span>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            @foreach(trans('product_statuses') as $productStatusKey => $productStatusLabel)
                                                <option value="{{ $productStatusKey }}" {{ (old('status') == $productStatusKey) ? 'selected' : '' }}>{{ $productStatusLabel }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Create unit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection