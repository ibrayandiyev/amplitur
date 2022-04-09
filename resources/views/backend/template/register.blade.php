<!DOCTYPE html>
<html lang="en">
    @include('backend.template.partials.head')


    <body class="card-no-border">
        <div class="preloader" style="display: none;">
            <div class="loader">
                <div class="lds-roller">
                </div>
            </div>
        </div>

        <section id="wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-light">
                    <div class="text-center">
                        <a href="https://amplitur30.amplitur.com/admin">
                            <b>
                                <img src="/frontend/images/estrutura/amp-travel-front-bgblue2.png" alt="homepage" style="padding: 0.3em" />
                            </b>
                            <span>
                        </a>
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">

                            <li class="nav-item hidden-sm-down"><span></span></li>
                        </ul>
                        <ul class="navbar-nav my-lg-0">
                            <li class="nav-item dropdown">
                                @include('backend.template.partials.language')
                            </li>
                            <li class="nav-item dropdown">
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="dropdown-user">
                                        <li role="separator" class="divider"></li>
                                        <li>
                                            <a href="#" id="btn-logout"><i class="fa fa-power-off"></i> {{ __('auth.logout') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <div class="page-wrapper" style="background-color: #e9e9e9;">
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
                <div>
                @include('backend.template.partials.footer')
                </div>
            </div>
        </section>
    </body>

    <script src="/backend/vendors/jquery/jquery.min.js"></script>
    <script src="/backend/vendors/bootstrap/js/popper.min.js"></script>
    <script src="/backend/vendors/bootstrap/js/bootstrap.min.js"></script>
    <script src="/backend/vendors/ps/perfect-scrollbar.jquery.min.js"></script>
    <script src="/backend/js/waves.js"></script>
    <script src="/backend/js/sidebarmenu.js"></script>
    <script src="/backend/vendors/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="/backend/vendors/sparkline/jquery.sparkline.min.js"></script>
    <script src="/backend/vendors/sweetalert/sweetalert.min.js"></script>
    <script src="/backend/vendors/bootstrap-inputmask/jquery.inputmask.min.js"></script>
    <script src="/backend/vendors/bootstrap-inputmask/inputmask.binding.js"></script>
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/vendors/select2/dist/js/select2.full.min.js"></script>
    <script src="/backend/vendors/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="/backend/vendors/typeahead.js-master/dist/typeahead.bundle.min.js"></script>
    <script src="/backend/vendors/typeahead.js-master/dist/typeahead-init.js"></script>
    <script src="/backend/js/custom.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    @stack('scripts')
    <script src="/backend/js/app.js?v=0.0.1"></script>
</html>
