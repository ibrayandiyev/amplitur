<table id="prebookingsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('resources.clients.name') }}</th>
            <th>{{ __('resources.events.name') }}</th>
            <th>{{ __('resources.prebookings.model.email') }}</th>
            <th>{{ __('resources.prebookings.model.city') }}</th>
            <th>{{ __('resources.prebookings.model.country') }}</th>
            <th class="text-center">{{ __('resources.prebookings.model.created_at') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($prebookings as $prebooking)
        <tr>
            <td class="text-uppercase"><a href="{{ route('backend.prebookings.edit', $prebooking) }}">{{ $prebooking->id }}</a></td>
            <td class="text-uppercase">{{ $prebooking->getName() }}</td>
            <td class="text-uppercase">{{ $prebooking->event->getTitle() }}</td>
            <td class="text-uppercase">{{ $prebooking->getEmail() }}</td>
            <td class="text-uppercase">{{ city($prebooking->city) }}</td>
            <td class="text-uppercase">{{ country($prebooking->country) }}</td>
            <td class="text-uppercase text-center">{{ $prebooking->createdAtLabel }}</td>
            <td class="text-center skip">
                <a href="{{ route('backend.prebookings.destroy', $prebooking) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
