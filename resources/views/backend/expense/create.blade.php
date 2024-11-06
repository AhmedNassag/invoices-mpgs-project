@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Expense') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.expense.index') }}" class="text-white"><i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Expense') }}</a> / 
                <a class="text-white">{{ __('Add') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form method="POST" action="<?=route('admin.expense.store')?>" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Title') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"/>
                                    @error('title')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Date') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="date" class="form-control datepicker @error('date') is-invalid @enderror" value="{{ old('date') }}"/>
                                    @error('date')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Amount') }}</label> <span class="text-danger">*</span>
                                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"/>
                                    @error('amount')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{ __('Receipt') }}</label>
                                    <div class="custom-file">
                                        <input name="receipt" type="file" class="custom-file-input upload-file-input @error('receipt') is-invalid @enderror" id="receipt">
                                        <label class="custom-file-label" for="receipt">{{ __('Choose file') }}</label>
                                    </div>
                                    @error('receipt')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>{{ __('Note') }}</label>
                                    <textarea name="note" id="note" cols="30" rows="3" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Create expense') }}</button>
                        </div>
                    </div>
                </form>
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