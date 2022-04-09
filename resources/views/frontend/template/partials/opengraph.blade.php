    <!-- Open Graph Tags -->
    <meta property="og:locale" content="{{ language() }}">
    <meta property="og:description" content="@yield('description', __('frontend.seo.home_metadesc'))">
    <meta property="og:image" content="@yield('image_url', __(asset('/frontend/images/estrutura/logo250x250og.png')))">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="@yield('width', '250')">
    <meta property="og:image:height" content="@yield('height', '250')">
    <meta property="og:url" content="@yield('url', __(asset('')))">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', __('frontend.seo.company_name') . ' - ' . __('frontend.seo.home_titulo'))">
    <meta property="og:site_name" content="{{__('frontend.seo.company_name')}}">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@ampliturturismo" />
    <meta name="twitter:creator" content="@ampliturturismo" />
