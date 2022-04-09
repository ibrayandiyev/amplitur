<table id="pagesTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.pages.model.name') }}</th>
            <th>{{ __('resources.page-groups.name') }}</th>
            <th>{{ __('resources.pages.model.is_active') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($pages as $page)
        <tr>
            <td class="text-uppercase"><a href="{{ route('backend.pages.edit', $page) }}">{{ $page->name }}</a></td>
            <td class="text-uppercase">{{ $page->pageGroup->name ?? '' }}</td>
            <td class="text-uppercase">{!! $page->isActiveLabel !!}</td>
            <td class="text-center skip">
                <a href="{{ route('backend.pages.destroy', $page) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
