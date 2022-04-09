<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('frontend.template.partials.document-header')

<body class="bd-documentos">
    <div id="site">
        <div id="corpo" class="css-documentos"  >
            @yield('content')
        </div>
    </div>

    @include('frontend.template.partials.scripts')
</body>
</html>