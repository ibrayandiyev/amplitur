<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('/favicon-180x180.png')}}">
    <link rel="apple-touch-icon" type="image/png" sizes="167x167" href="{{ asset('/favicon-167x167.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-48x48.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-192x192.png')}}">
    <link rel="shortcut icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png')}}">
    <title>{{__('resources.head') }}</title>
    <link href="/backend/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/backend/css/pages/login-register-lock.css" rel="stylesheet">
    <link href="/backend/css/master-stylesheet.css" rel="stylesheet">
    <link href="/backend/css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <link href="/backend/css/amplitur.css" id="theme" rel="stylesheet">
</head>

<body class="card-no-border">
    <div class="preloader">
        <div class="loader">
            <div class="lds-roller">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
            </div>
        </div>
    </div>

    <section id="wrapper">
        <div class="page-wrapper">
            <div class="login-box card">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    @include('backend.template.partials.footer')

    <script src="/backend/vendors/jquery/jquery.min.js"></script>
    <script src="/backend/vendors/bootstrap/js/popper.min.js"></script>
    <script src="/backend/vendors/bootstrap/js/bootstrap.min.js"></script>
    @stack('scripts')

</body>
</html>
