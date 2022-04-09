<table id="newslettersTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>{{ __('resources.newsletters.model.name') }}</th>
            <th>{{ __('resources.newsletters.model.email') }}</th>
            <th class="text-center">{{ __('resources.newsletters.model.created_at') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($newsletters as $newsletter)
            <tr>
                <td class="text-uppercase text-center">{{ $newsletter->id }}</td>
                <td class="text-uppercase">{{ $newsletter->name }}</td>
                <td class="text-lowercase">{{ $newsletter->email }}</td>
                <td class="text-center">{{ $newsletter->createdAtLabel }}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.reports.newsletters.destroy', $newsletter->id) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#newslettersTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush
