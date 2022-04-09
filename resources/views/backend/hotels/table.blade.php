<table id="hotelsTable" class="table table-bordered table-striped table-hover table-linked-row">
    <thead>
        <tr>
            <th>{{ __('resources.hotels.model.name') }}</th>
            @if (auth()->user()->isMaster())
            <th>{{ __('resources.hotels.model.provider') }}</th>
            @endif
            <th>{{ __('resources.hotels.model.registry_type') }}</th>
            <th>{{ __('resources.hotels.model.category') }}</th>
            <th>{{ __('resources.hotels.model.checkin') }}</th>
            <th>{{ __('resources.hotels.model.checkout') }}</th>
            <th class="text-center">{{ __('resources.hotels.model.status') }}</th>
            <th class="text-center">{{ __('resources.hotels.model.actions') }}</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#hotelsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.hotels.datable') }}?XDEBUG_SESSION_START=PHPSTORM",
                columns: [
                    { data: 'name' },
                    @if (auth()->user()->isMaster())
                    { data: 'provider' },
                    @endif
                    { data: 'registry_type' },
                    { data: 'category_id' },
                    { data: 'checkin' },
                    { data: 'checkout' },
                    { data: 'status' },
                    { data: function ( row, type, val, meta ) {
                        var url_destroy = "{{ route('backend.hotels.destroy', 0) }}" + row.id;
                        var url_edit    = "{{ route('backend.hotels.edit', '##') }}".replace("##", row.id);
                        var buttons = '<a href="'+url_destroy+'"  class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>';
                        buttons += ' <a href="'+url_edit+'"  class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>';
                        return buttons;
                    }}
                ],
            });

        });
    </script>
@endpush
