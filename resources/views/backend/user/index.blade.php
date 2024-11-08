@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-users"></i> {{ __('user.user') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="fas fa-users fa-sm text-white-50"></i> {{ __('user.user') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row">
                            
                        <div class="col-sm-6 pull-left">
                            @can('user_create')
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add User') }}</a>
                                </h6>
                            @endcan
                        </div>

                        <div class="col-sm-3 offset-sm-3">
                            <div class="form-group mb-0">
                                <select class="form-control" id="userrole">
                                    <option data-url="<?=route('admin.user.role', 0)?>" value="0" {{ $selectRoleID == 0 ? 'selected' : ''}}>{{ __('Select a Role') }}</option>
                                    @if(!blank($roles))
                                        @foreach($roles as $role)
                                            <option data-url="<?=route('admin.user.role', $role->id)?>" value="{{ $role->id }}" {{ $selectRoleID == $role->id ? 'selected' : ''}}>{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('user.role') }}</th>
                                        @if(auth()->user()->can('user_edit') || auth()->user()->can('user_destroy') || auth()->user()->can('user_show'))
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!blank($users))
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $loop->index+1 }}</td>
                                                <td>
                                                    <img alt="image" src="{{ $user->image }}" class="rounded-circle img-profile-list">
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role_name ?? '' }}</td>
                                                @if(auth()->user()->can('user_edit') || auth()->user()->can('user_destroy') || auth()->user()->can('user_show'))
                                                    <td>
                                                        @can('user_show')
                                                            <a href="{{ route('admin.user.show', $user) }}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View"><i class="far fa-check-square"></i></a>
                                                        @endcan

                                                        @can('user_edit')
                                                            <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                                                        @endcan

                                                        @can('user_destroy')
                                                            <form class="d-inline-block delete" action="{{ route('admin.user.destroy', $user) }}" method="POST">
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
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('user.role') }}</th>
                                        @if(auth()->user()->can('user_edit') || auth()->user()->can('user_destroy') || auth()->user()->can('user_show'))
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