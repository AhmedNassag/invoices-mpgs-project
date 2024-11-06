@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-cart-plus"></i> {{ __('Product') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.product.index') }}" class="text-white"><i class="fas fa-cart-plus fa-sm text-white-50"></i> {{ __('Product') }}</a> /
                <a class="text-white">{{ __('Edit') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <form method="POST" action="<?=route('admin.product.update', $product)?>" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Name') }}</label> <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}"/>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Price') }}</label> <span class="text-danger">*</span>
                                    <input type="number" name="price" class="form-control pricepicker @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}"/>
                                    @error('price')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Unit') }}</label> <span class="text-danger">*</span>
                                    <select name="unit_id" class="form-control @error('unit_id') is-invalid @enderror">
                                        <option value="0">{{ __('Select Unit') }}</option>
                                        @if(!blank($units))
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ (old('unit_id', $product->unit_id) == $unit->id) ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status">{{ __('Status') }}</label> <span class="text-danger">*</span>
                                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                        @foreach(trans('product_statuses') as $productStatusKey => $productStatusLabel)
                                            <option value="{{ $productStatusKey }}" {{ (old('status', $product->status) == $productStatusKey) ? 'selected' : '' }}>{{ $productStatusLabel }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="code">{{ __('Code') }}</label> <span class="text-danger">*</span>
                                    <input id="code" type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $product->code) }}"/>
                                    @error('code')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{ __('Image') }}</label>
                                    <div class="custom-file">
                                        <input name="image" type="file" class="custom-file-input upload-file-input @error('image') is-invalid @enderror" id="uploadImg">
                                        <label class="custom-file-label" for="image">{{ __('Choose file') }}</label>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    <img id="prevImg" class="img-thumbnail image-width mt-2 mb-2 setting-logo" src="{{ $product->image }}" alt="">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>{{ __('Description') }}</label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Update product') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('footer_scripts')
    <script src="{{ asset('backend/js/item.js') }}"></script>
@endpush