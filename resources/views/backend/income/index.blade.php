@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-university"></i> {{ __('Income') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="fas fa-university fa-sm text-white-50"></i> {{ __('Income') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    @can('income_create')
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-sm-6 pull-left">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <a href="{{ route('admin.income.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add income') }}</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endcan
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Receipt') }}</th>
                                        @if(auth()->user()->can('income_edit') || auth()->user()->can('income_destroy'))
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!blank($incomes))
                                        @foreach($incomes as $income)
                                            <tr>
                                                <td>{{ $loop->index+1 }}</td>
                                                <td>{{ $income->title }}</td>
                                                <td>{{ $income->date->format('d M Y') }}</td>
                                                <td>{{ green_number_format($income->amount) }}</td>
                                                <td>
                                                    @if($income->receiptUrl)
                                                        <a href="{{ route('admin.income.download', $income) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="{{ $income->title }}"><i class="fas fa-download"></i> {{ __('Download') }}</a>
                                                    @endif
                                                </td>
                                                @if(auth()->user()->can('income_edit') || auth()->user()->can('income_destroy'))
                                                    <td>
                                                        @can('income_edit')
                                                            <a href="{{ route('admin.income.edit', $income) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                                                        @endcan
                                                        @can('income_destroy')
                                                            <form class="d-inline-block delete" action="{{ route('admin.income.destroy', $income) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Receipt') }}</th>
                                        @if(auth()->user()->can('income_edit') || auth()->user()->can('income_destroy'))
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('header_css')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('footer_scripts')
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endpush