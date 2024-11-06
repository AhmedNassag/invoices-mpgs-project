@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="far fa-bell"></i> {{ __('Notification') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="far fa-bell fa-sm text-white-50"></i> {{ __('Notification') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!blank($notificationList))
                                        @foreach($notificationList as $notification)
                                            <tr>
                                                <td>{{ $loop->index+1 }}</td>
                                                <td>{{ data_get($notification, 'data.subject') }}</td>
                                                <td>{{ $notification->created_at->format('d M Y h:i A') }}</td>
                                                <td>
                                                    <a href="{{ url(data_get($notification, 'data.url')) }}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View">
                                                        <i class="far fa-check-square"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Action') }}</th>
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