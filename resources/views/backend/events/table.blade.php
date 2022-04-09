<table id="eventsTable" class="table table-bordered table-striped table-hover table-linked-row">
    <thead>
        <tr>
            <th>{{ __('resources.events.model.name') }}</th>
            <th>{{ __('resources.events.model.category') }}</th>
            <th>{{ __('resources.events.model.city') }}</th>
            <th>{{ __('resources.events.model.state') }}</th>
            <th>{{ __('resources.events.model.country') }}</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#eventsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.events.datable') }}",
                columns: [
                    { data: 'name' },
                    { data: 'category_id' },
                    { data: 'city' },
                    { data: 'state' },
                    { data: 'country' },
                ],
            });

            $('#eventsTable tbody').on('click', 'tr td:not(.skip)', function () {
                window.location = '{{ route('backend.events.index') }}/' + table.row(this).data().id;
            });
        });
    </script>
@endpush