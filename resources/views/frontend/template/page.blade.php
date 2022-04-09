<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('frontend.template.partials.head')

<body class="bd-home bd-generic bd-pt_BR">
    <div class="site">
        @include('frontend.template.partials.header')
        @include('frontend.template.partials.search')

        @include('frontend.template.partials.alert')

        @yield('content')

        @include('frontend.template.partials.footer')
    </div>

    @include('frontend.template.partials.scripts')
</body>
</html>