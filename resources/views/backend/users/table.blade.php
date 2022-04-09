<table id="usersTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.label.nickname') }}</th>
            <th>{{ __('resources.users.model.name') }}</th>
            <th>{{ __('resources.users.model.email') }}</th>
            <th class="text-center">{{ __('resources.users.model.status') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td><a href="{{ route('backend.configs.users.edit', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{!! $user->statusLabel !!}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.configs.users.destroy', $user) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            $('#usersTable').DataTable();
        });
    </script>
@endpush
