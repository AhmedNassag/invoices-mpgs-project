@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Barcode') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Barcode') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.barcode.filter') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>{{ __('Products') }}*</label>
                            <select name="product_id" id="product_id" class="form-control select2 @error('product_id') is-invalid @enderror">
                                <option value="0">{{ __('Please Select') }}</option>
                                @if(!blank($products))
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ (old('product_id', $set_product_id) == $product->id) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label to="quantity">{{ __('Quantity') }}*</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $set_quantity) }}"/>
                            @error('quantity')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-primary">{{ __('Get Report ') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($showView)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="m-0">
                        {{ __('Barcode') }}
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-success" onclick="printDiv()">{{ __('Print') }}</button>
                            <a href="{{ route('admin.barcode.pdf', [$set_product_id, $set_quantity]) }}" target="_blank" class="btn btn-warning">{{ __('PDF') }}</a>
                        </div>

                    </h2>
                </div>
                <div class="card-body" id="printDiv">
                    <div class="barcode-list">
                        @for($i = 1; $i <= $set_quantity; $i++)
                            <div class="barcode-item">
                                <p>{{ $selectedProduct->code }} - {{ $i }}</p>
                                <img src="{{ asset('barcode/' . $selectedProduct->code . '.jpg') }}" alt="">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- /.container-fluid -->
@endsection

@push('header_css')
    <link href="{{ asset('backend/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .barcode-item {
            width: 150px;
            margin: 10px 15px;
            float: left;
            overflow: hidden;
            text-align: center;
        }
        .barcode-item p {
            margin-bottom: 2px;
        }
        .barcode-item img {
            width: 150px;
            height: 40px;
        }
    </style>
@endpush

@push('footer_scripts')
    <script src="{{ asset('backend/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/report.js') }}"></script>
@endpush
