<script>
var csrfToken = $('[name="_token"]');
            
    function refreshToken(){
        $.get("{{ route('frontend.renew-csrf-token') }}").done(function(data){
            csrfToken.val(data); // the new token
        });
    }

    setInterval(refreshToken, 3000000); // 1 hour 
</script>