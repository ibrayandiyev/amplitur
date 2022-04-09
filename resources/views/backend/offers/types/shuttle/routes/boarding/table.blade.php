@php
    $shuttleBoardingLocations = $shuttleRoute->shuttleBoardingLocations;
@endphp

@if (count($shuttleBoardingLocations) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th class="text-center">{{ __('resources.shuttle-routes-boarding.model.boarding_at') }}</th>
        <th>{{ __('resources.shuttle-routes-boarding.model.complement') }}</th>
        <th>{{ __('resources.shuttle-routes-boarding.model.address') }}</th>
        <th class="text-center">{{ __('resources.shuttle-routes-boarding.model.net_sale') }}</th>
        <th class="text-center">{{ __('resources.shuttle-routes-boarding.model.sale_price') }}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($shuttleBoardingLocations->sortBy("price") as $boardingLocation)
        @php
            $address = $boardingLocation->address;
        @endphp
        <tr>
            <td width="15%" class="text-center"><a href="{{ route('backend.providers.companies.offers.shuttle.editBoardingLocation', [$provider, $company, $offer, $shuttleRoute, $boardingLocation]) }}">
                {{ $boardingLocation->boardingAtLabel }}
                {{__('frontend.reservas.as')}} {{ $boardingLocation->boardingAtTimeLabel }}</a>
            </td>
            <td width="20%">{{ ($address->complement) }}</td>
            <td width="30%">{{ city($address->address) }} - {{city($address->city) }} - {{ state($address->country, $address->state) }} - {{ country($address->country) }}</td>
            <td width="8%" class="text-center">{{ $boardingLocation->extendedPrice }}</td>
            <td width="8%" class="text-center"><span class="sale-price">{{ $boardingLocation->extendedValuePrice }}</SPAN></td>
            <td width="10%" class="text-center skip">
                @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.shuttle.destroyBoardingLocation', [$provider, $company, $offer, $shuttleRoute, $boardingLocation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
           {{ __('resources.shuttle-routes-boarding.empty') }}
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
