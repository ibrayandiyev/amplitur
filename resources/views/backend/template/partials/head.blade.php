
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @stack('metas')
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('/favicon-180x180.png')}}">
    <link rel="apple-touch-icon" type="image/png" sizes="167x167" href="{{ asset('/favicon-167x167.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-48x48.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-192x192.png')}}">
    <link rel="shortcut icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png')}}">
    <title>{{__('resources.head') }}</title>
    <link href="{{ asset('/backend/vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/master-stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/multiselect/css/multi-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/typeahead.js-master/dist/typehead-min.css') }}" rel="stylesheet">
    <link href="{{ asset('/backend/vendors/multiselect/css/multi-select.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('/backend/css/amplitur.css') }}" id="theme" rel="stylesheet">
    @stack('head-scripts')
</head>














