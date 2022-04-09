<!DOCTYPE html>
<html lang="en">
    @include('backend.template.partials.head')


    <body class="card-no-border">
        <div class="preloader">
            <div class="loader">
                <div class="lds-roller">
                </div>
            </div>
        </div>

        <div id="main-wrapper">
            @include('backend.template.partials.header')
            @include('backend.template.partials.navigation')
            <div class="page-wrapper">
                <div class="container-fluid">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <h4 class="text-success"><i class="fa fa-check-circle"></i> {{ __('messages.success')  }}</h4>
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(session('warning'))
                    <div class="alert alert-warning">
                        <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning')  }}</h4>
                        {{ session('warning') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{ __('messages.error')  }}</h4>
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(session('info'))
                    <div class="alert alert-info">
                        <h4 class="text-info"><i class="fa fa-check-circle"></i> {{ __('messages.info')  }}</h4>
                        {{ session('info') }}
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{ __('messages.error')  }}</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @yield('content')
                </div>
                @include('backend.template.partials.footer')
            </div>
        @include('backend.template.partials.scripts')
    </body>
</html>
