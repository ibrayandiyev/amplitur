    <head>
    @include('frontend.template.scripts.google-tag-manager-head')
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', __('frontend.seo.company_name') . ' - ' . __('frontend.seo.home_titulo'))</title>
    <meta name="description" content="@yield ('description', __('frontend.seo.home_metadesc'))">
	<meta name="keywords" content="@yield ('keywords', __('frontend.seo.home_keywords'))">
	<meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700|Titillium+Web:600,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/brands.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/brands.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/solid.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css" rel="stylesheet">
    <link href="{{ asset('/frontend/css/amplitur.css') }}" rel="stylesheet">
    <link href="{{ asset('/frontend/css/documentos.css') }}" rel="stylesheet">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('/favicon-180x180.png')}}">
    <link rel="apple-touch-icon" type="image/png" sizes="167x167" href="{{ asset('/favicon-167x167.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-48x48.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-192x192.png')}}">
    <link rel="shortcut icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png')}}">
    <link rel="manifest" href="{{ asset('/site.webmanifest')}}">
    <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg')}}" color="#0e7cb2">
    <meta name="apple-mobile-web-app-title" content="{{__('frontend.seo.company_name')}}">
    <meta name="application-name" content="{{__('frontend.seo.company_name')}}">
    <meta name="msapplication-TileColor" content="#0e7cb2">
    <meta name="theme-color" content="#0e7cb2">

    <link rel="canonical" href="@yield('url_canonical',  __(asset('')))">
    <link rel="alternate" hreflang="es" href="@yield('url_canonical_es',  __(asset('')))">
    <link rel="alternate" hreflang="en" href="@yield('url_canonical_en',  __(asset('')))">
    <link rel="alternate" hreflang="pt-br" href="@yield('url_canonical_pt-br',  __(asset('')))">
    <link rel="alternate" hreflang="x-default" href="@yield('url_canonical_default',  __(asset('')))">

    @include('frontend.template.partials.opengraph')
    @include('frontend.template.scripts.structure-data-company')

    @yield('structure-data')

    @stack('styles')

    <script type="text/javascript">
        const baseurl = '{{ route('frontend.index') }}/';
    </script>

</head>

