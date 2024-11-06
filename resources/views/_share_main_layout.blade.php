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

        <!-- Custom styles for this template-->
        <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet" />
    </head>

    <body class="sidebar-toggled">
        <div id="app">
            <!-- Page Wrapper -->
            <div id="wrapper">

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">
                    <!-- Main Content -->
                    <div id="content">

						@yield('content')

                    </div>
                    <!-- End of Main Content -->
                    <!-- Footer -->
                    <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>{{ setting('copyright_by') }}</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->
                </div>
                <!-- End of Content Wrapper -->
            </div>
            <!-- End of Page Wrapper -->
        </div>
    </body>
</html>
