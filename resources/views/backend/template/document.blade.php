<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AMP Travels </title>
    <link href="{{ asset('frontend/css/documentos.css') }}" rel="stylesheet" media="all">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('/favicon-180x180.png')}}">
    <link rel="apple-touch-icon" type="image/png" sizes="167x167" href="{{ asset('/favicon-167x167.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-48x48.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-192x192.png')}}">
    <link rel="shortcut icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png')}}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg') }}" color="#0e7cb2">
    <meta name="apple-mobile-web-app-title" content="AMP Travels ">
    <meta name="application-name" content="AMP Travels ">
    <meta name="msapplication-TileColor" content="#0e7cb2">
    <meta name="theme-color" content="#0e7cb2">
</head>

<body class="bd-documentos">
    <div id="site">
        <div id="corpo" class="css-documentos" @if ($isPdf ?? false) style="width: 75%" @endif>
            @yield('content')
        </div>
    </div>
</body>
