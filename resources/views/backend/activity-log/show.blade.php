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
                                        <th>{{ __('Field Name') }}</th>
                                        <th>{{ __('Old Value') }}</th>
                                        <th>{{ __('New Value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityChanges as $activityChange)
                                        <tr>
                                            <td>{{ $activityChange['key'] }}</td>
                                            <td>{{ $activityChange['old'] }}</td>
                                            <td>{{ $activityChange['new'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
