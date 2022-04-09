@php
    $bustripBoardingLocations = $bustripRoute->bustripBoardingLocations;
@endphp

@if (count($bustripBoardingLocations) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.bustrip-routes-boarding.model.address') }}</th>
        <th class="text-center">{{ __('resources.bustrip-routes-boarding.model.boarding_at') }}</th>
        <th class="text-center">{{ __('resources.bustrip-routes-boarding.model.travel_time') }}</th>
        <th class="text-center">{{ __('resources.bustrip-routes-boarding.model.net_sale') }}</th>
        <th class="text-center">{{ __('resources.bustrip-routes-boarding.model.sale_price') }}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($bustripBoardingLocations as $boardingLocation)
        @php
            $address = $boardingLocation->address;
        @endphp
        <tr>
            <td><a href="{{ route('backend.providers.companies.offers.bustrip.editBoardingLocation', [$provider, $company, $offer, $bustripRoute, $boardingLocation]) }}">{{ city($address->city) }} - {{ state($address->country, $address->country) }} - {{ country($address->country) }}</a></td>
            <td width="15%" class="text-center">
                {{ $boardingLocation->boardingAtLabel }}
                {{__('frontend.reservas.as')}} {{ $boardingLocation->boardingAtTimeLabel }}
            </td>
            <td width="20%" class="text-center">{{ $boardingLocation->travel_time }}</td>
            <td class="text-center">{{ $boardingLocation->extendedPrice }}</td>
            <td class="text-center"><span class="sale-price">{{ $boardingLocation->extendedValuePrice }}</span></td>
            <td width="10%" class="text-center skip">
                @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.bustrip.destroyBoardingLocation', [$provider, $company, $offer, $bustripRoute, $boardingLocation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <div class="row m-b-10">
        <div class="col-md-12">
           {{ __('resources.bustrip-routes-boarding.empty') }}
        </div>
    </div>
@endif

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#routesTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
