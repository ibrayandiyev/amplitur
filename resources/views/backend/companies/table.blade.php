<table id="companiesTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="40%">{{ __('resources.companies.model.name') }}</th>
            <th width="19%">{{ __('resources.address.city') }}</th>
            <th width="15%">{{ __('resources.address.country') }}</th>
            <th width="15%" class="text-center">{{ __('resources.companies.model.created_at') }}</th>
            <th width="5%" class="text-center">{{ __('resources.companies.model.status') }}</th>
            <th width="5%" class="text-center">{{ __('resources.companies.model.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $company)
            @php
                $address = $company->address;
                $city = ($address) ? $address->city() : null;
                $state = ($address) ? $address->state() : null;
                $country = ($address) ? $address->country() : null;
            @endphp
            <tr>
                <td><a href="{{ route('backend.providers.companies.edit', [$company->provider, $company]) }}">{{ $company->company_name }}</a></td>
                <td>
                    {{ city($city) }} ({{ state($country, $state) }})
                </td>
                <td>
                    {{ country($country) }}
                </td>
                <td class="text-center">
                    {{ $company->createdAtLabel }}
                    <small class="text-muted">{{ $company->createdAtTimeLabel }}</small>
                </td>
                <td class="text-center">{!! $company->statusLabel !!}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.providers.companies.offers.index', [$company->provider, $company]) }}"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.offers.name-plural') }}">
                        <i class="fa fa-folder-o"></i>
                    </a>
                    @if(user()->canManageCompanies())
                    <a href="{{ route('backend.providers.companies.destroy', [$company->provider, $company]) }}"  token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            $('#companiesTable').DataTable({
                // searching: false,
            });
        });
    </script>
@endpush
