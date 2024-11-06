@extends('_main_layout')

@section('content')        

    <!-- Services-->
    <section class="page-section">
        <div class="container">
            <h2 class="text-center mt-0 text-danger">{{ __('403') }}</h2>
            <div class="text-center">
                <div class="text-danger">
                    <h1>{{ __('Oops') }}</h1>
                    {{ __('You doesn\'t have permission to use this feature.') }}
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.dashboard.index') }}" class="btn btn-primary btn-sm">
                        <span class="fas fa-undo-alt"></span> 
                        {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection