@php
    $longtripRoutes = $offer->longtripRoutes;
    $longtripRoutes = $longtripRoutes->sortBy("name");
@endphp

@if (count($longtripRoutes) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.longtrip-routes.model.name') }}</th>
        <th class="text-center">{{ __('resources.longtrip-routes.model.capacity') }}</th>
        <th class="text-center">{{__('resources.longtrip-routes.model.fields.event_day')}}</th>
        <th>{{__('resources.longtrip-routes-boarding.model.boarding_at')}}</th>
        <th>{{__('resources.longtrip-routes-boarding.model.ends_at')}}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($longtripRoutes as $route)
        <tr>
            <td width="25%" class=""><a href="{{ route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $route]) }}"><!-- {{ $route->id }} --> {{ $route->name }}</a></td>
            <td width="5%" class="text-center">{{ $route->capacity }}</td>
            <td width="13%" class="text-center day-show">{{ $route->getLongtripRouteDatesAttribute() }}</td>
            <td width="30%" class="">
                {!! $route->boardingLocationsList !!}
            </td>
            <td width="20%" class="">
                {!! $route->boardingLocationsEndsList !!}
            </td>
            <td width="7%" class="text-center skip">
                @if ($canChange)
                    <a href="{{ route('backend.providers.companies.offers.longtrip.createBoardingLocation', [$provider, $company, $offer, $route]) }}"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.longtrip-routes-boarding.create') }}">
                        <i class="icon-Bus-2"></i>

                    </a>
                    <a href="{{ route('backend.providers.companies.offers.longtrip.destroyRoute', [$provider, $company, $offer, $route]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            {{__('resources.no_routes')}}
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
