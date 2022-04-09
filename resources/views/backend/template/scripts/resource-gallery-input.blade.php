<script type="text/javascript">
    $(document).ready(function () {
        $('input[name="uploaded_images[]"]').on('change', function(e){
            var files = e.target.files;
            var container = $('#uploaded-files');

            container.empty();

            for (var i = 0; i < files.length; i++) {
                container.append('<span class="label label-light-inverse mr-1">'+ files[i].name +'</span>');
            }
        });
    });
</script>