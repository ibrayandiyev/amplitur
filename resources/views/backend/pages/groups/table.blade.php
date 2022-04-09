<table id="pageGroupsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.page-groups.model.name') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($pageGroups as $pageGroup)
        <tr>
            <td class="text-uppercase"><a href="{{ route('backend.pages.groups.edit', $pageGroup) }}">{{ $pageGroup->name }}</a></td>
            <td class="text-center skip">
                <a href="{{ route('backend.pages.groups.destroy', $pageGroup) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
