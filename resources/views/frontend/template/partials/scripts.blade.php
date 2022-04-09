<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
@stack('scripts')
<script src="{{ asset('/frontend/js/amplitur.min.js') }}"></script>
<script src="{{ asset('/frontend/js/amplitur_booking_rules.min.js') }}"></script>

@if (config('app.env') == 'production')
    @include('frontend.template.scripts.google-tag-manager')
    @include('frontend.template.scripts.google-conversion')
    @include('frontend.template.scripts.chat')
@endif
@include('frontend.template.scripts.csrf-token-renew')