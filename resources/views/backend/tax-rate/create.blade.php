@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-user-secret"></i> {{ __('Tax Rate') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.tax-rate.index') }}" class="text-white"><i class="fas fa-user-secret fa-sm text-white-50"></i> {{ __('Tax Rate') }}</a> /
                <a class="text-white">{{ __('Add') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-6 col-md-6">
                <form method="POST" action="<?=route('admin.tax-rate.store')?>">
                    @csrf
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
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
                                        <label>{{ __('Percent') }}</label> <span class="text-danger">*</span>
                                        <input type="number" min="0" name="percent" class="form-control @error('percent') is-invalid @enderror" value="{{ old('percent') }}" step=".01"/>
                                        @error('percent')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Status') }}</label> <span class="text-danger">*</span>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            @foreach(trans('tax_statuses') as $taxStatusKey => $taxStatus)
                                                <option value="{{ $taxStatusKey }}" {{ (old('status') == $taxStatusKey) ? 'selected' : '' }}>{{ $taxStatus }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('Description') }}</label>
                                        <textarea name="description" id="description" cols="30" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Create tax rate') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
