<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}"/>

        <link rel="shortcut icon" href="{{ green_site_logo() }}">

        <title>@yield('title',  'Green Inventory')</title>

        <!-- Custom fonts for this template-->
        <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

        @stack('header_css')

        <!-- Custom styles for this template-->
        <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('backend/vendor/izitoast/dist/css/iziToast.min.css') }}" />

        <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet" />

        @stack('header_scripts')
    </head>

    <body class="<?=(setting('site_sidebar') == 0) ? 'sidebar-toggled' : '' ?>">
        <div id="app">
            <!-- Page Wrapper -->
            <div id="wrapper">

                @include('backend.components._page_sidebar')

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">
                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                            <!-- Sidebar Toggle (Topbar) -->
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>

                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">

                                <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bell fa-fw"></i>
                                        <!-- Counter - Alerts -->
                                        <span class="badge badge-danger badge-counter">
                                            {{ auth()->user()->unreadNotifications()->count() }}+
                                        </span>
                                    </a>

                                    <!-- Dropdown - Alerts -->
                                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                        <h6 class="dropdown-header">
                                            {{ __('Notifications Center') }}
                                        </h6>
                                        @if(!blank($notifications))
                                            @foreach($notifications as $notification)
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.notification.show', $notification->id) }}">
                                                    <div class="dropdown-list-image mr-3">
                                                        <img class="rounded-circle" src="{{ data_get($notification, 'creator.image') }}" alt="...">
                                                    </div>
                                                    <div class="font-weight-bold">
                                                        <div class="text-truncate">{{ data_get($notification, 'data.subject') }}</div>
                                                        <div class="small text-gray-500">{{ $notification->label_created_at }}</div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="text-center p-2 d-block">{{ __('You doesn\'t have any unread notification.') }}</span>
                                        @endif
                                        <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.notification.index') }}">{{ __('Show All Notification') }}</a>
                                    </div>
                                </li>

                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">
                                    <a class="nav-link dropdown-toggle" href="{{ route('admin.profile.index') }}" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? '' }}</span>
                                        <img class="img-profile rounded-circle" src="{{ auth()->user()->image ?? '' }}" />
                                    </a>
                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                            {{ __('global.profile') }}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            {{ __('global.logout') }}
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </nav>
