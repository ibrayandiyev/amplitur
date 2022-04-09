<script type="text/javascript">
    $(document).ready(function () {
        $('select[name="event_id"]').select2({
            @if(old("event_id") != "")
            data: eventData,
            @endif
            ajax: {
                url: "{{ route('backend.events.jsonFilter') }}",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: (params) => {
                    return {
                        _token: $('input[name="_token"]').val(),
                        q: params.term
                    };
                },
                processResults: (response) => {
                    return  {
                        results: response
                    };
                },
            },
            templateResult: (result) => {
                return result.name;
            },
            templateSelection: (result) => {
                return result.name;
            }
        }).on("select2:select", function(e){
            if(e.params.data.category.flags.DURATION !== undefined){
                if(e.params.data.category.flags.DURATION == 'one-day'){
                    let eventDuration = e.params.data.category.flags.DURATION;
                    if(handleEventDuration !== undefined){
                        handleEventDuration(eventDuration);
                    }
                }

            }
        });

    });
</script>