@php
    $longtripBoardingLocations = $longtripRoute->longtripBoardingLocations;
@endphp

@if (count($longtripBoardingLocations) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.longtrip-routes-boarding.model.address') }}</th>
        <th class="text-center">{{ __('resources.longtrip-routes-boarding.model.boarding_at') }}</th>
        <th class="text-center">{{ __('resources.longtrip-routes-boarding.model.travel_time') }}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($longtripBoardingLocations as $boardingLocation)
        @php
            $address = $boardingLocation->address;
        @endphp
        <tr>
            <td><a href="{{ route('backend.providers.companies.offers.longtrip.editBoardingLocation', [$provider, $company, $offer, $longtripRoute, $boardingLocation]) }}">{{ city($address->city) }} - {{ state($address->country, $address->country) }} - {{ country($address->country) }}</a></td>
            <td width="15%" class="text-center">
                {{ $boardingLocation->boardingAtLabel }}
                às {{ $boardingLocation->boardingAtTimeLabel }}
            </td>
            <td width="20%" class="text-center">
                {{ $boardingLocation->endsAtLabel }}
                {{__('frontend.reservas.as')}} {{ $boardingLocation->endsAtTimeLabel }}
            </td>
            <td width="10%" class="text-center skip">
                @if ($canChange)
                    <a href="{{ route('backend.providers.companies.offers.longtrip.destroyBoardingLocation', [$provider, $company, $offer, $longtripRoute, $boardingLocation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
           {{ __('resources.longtrip-routes-boarding.empty') }}
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
