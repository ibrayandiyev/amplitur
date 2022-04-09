<table id="promocodeGroupsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.promocode-groups.model.name') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($promocodeGroups as $promocodeGroup)
        <tr>
            <td class="text-uppercase">
                <a href="{{ route('backend.promocodes.groups.edit', $promocodeGroup) }}">{{ $promocodeGroup->name }}</a>
            </td>
            <td class="text-center skip">
                @if (!$promocodeGroup->hasPromocodes())
                    <a href="{{ route('backend.promocodes.groups.destroy', $promocodeGroup) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#promocodeGroupsTable').DataTable();
        });
    </script>
@endpush
