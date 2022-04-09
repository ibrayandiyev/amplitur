<script type="text/javascript">
    function deleteLogs(e) {
        let logs = $('.deleteLogs:checked');
        let url = '{{ $deleteLogRouteUrl }}';
        let csrf = $('input[name="_token"]').val();
        let form = $('<form>', {
            'action': url,
            'method': 'POST',
        });

        if (logs == undefined) {
            return;
        }

        for (let i = 0; i < logs.length; i ++) {
            form.append(`<input type="hidden" name="deleteLogs[]" value='${$(logs[i]).val()}' />`)
        }

        form
        .append(`<input type="hidden" name="_token" value="${csrf}" />`)
        .appendTo(document.body);

        form.submit();
    }
</script>