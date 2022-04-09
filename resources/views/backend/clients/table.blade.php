
<table id="clientsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="30%">{{ __('resources.clients.model.name') }}</th>
            <th width="19%">{{ __('resources.address.city') }}</th>
            <th width="15%">{{ __('resources.address.country') }}</th>
            <th width="10%" class="text-center">{{ __('resources.clients.model.created_at') }}</th>
            <th width="10%" class="text-center">{{ __('resources.clients.model.type') }}</th>
            <th width="8%" class="text-center">{{ __('resources.clients.model.is_active') }}</th>
            <th width="8%" class="text-center">{{ __('resources.clients.model.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
            @php
                $address = $client->address;
                $city = ($address) ? $address->city() : null;
                $state = ($address) ? $address->state() : null;
                $country = ($address) ? $address->country() : null;
            @endphp
            <tr>
                <td class="">
                    <a href="{{ route('backend.clients.edit', $client->id) }}">{{ name($client) }}</a>
                </td>
                <td class="">
                    {{ city($city) }} ({{ state($country, $state) }})
                </td>
                <td class="">
                    {{ country($country) }}
                </td>
                <td class="text-center">
                    {{ $client->createdAtLabel }}
                    <small class="text-muted">{{ $client->createdAtTimeLabel }}</small>
                </td>
                <td class="text-center">{!! $client->typeLabel !!}</td>
                <td class="text-center">{!! $client->isActiveLabel !!}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.clients.loginAsCustomer', $client) }}" target='_blank' class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="{{ __('messages.login_as_customer') }}">
                        <i class="mdi mdi-account-key"></i>
                    </a>
                    <a href="{{ route('backend.clients.destroy', $client) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </a>

                </tr>
            </td>
        @endforeach
    </tbody>
</table>
