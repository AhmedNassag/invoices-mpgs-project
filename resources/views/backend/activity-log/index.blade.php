@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-history"></i> {{ __('activity_log.activity_log') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="fas fa-history fa-sm text-white-50"></i> {{ __('activity_log.activity_log') }}</a>
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
                                        <th>{{ __('Log Name') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!blank($activities))
                                        @foreach($activities as $activity)
                                            <tr>
                                                <td>{{ $loop->index+1 }}</td>
                                                <td>{{ $activity->log_name }}</td>
                                                <td>{{ $activity->subject->name }}</td>
                                                <td>{{ $activity->create_date }}</td>
                                                <td>
                                                    @if(!blank($activity->changes))
                                                        <a href="{{ route('admin.activity-log.show', $activity) }}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View"><i class="far fa-check-square"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Log Name') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Date') }}
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
