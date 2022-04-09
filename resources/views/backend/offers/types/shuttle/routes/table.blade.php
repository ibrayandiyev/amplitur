@php
    $shuttleRoutes = $offer->shuttleRoutes;
    $shuttleRoutes = $shuttleRoutes->sortBy("name");

@endphp

@if (count($shuttleRoutes) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.shuttle-routes.model.name') }}</th>
        <th class="text-center">{{ __('resources.shuttle-routes.model.capacity') }}</th>
        <th class="text-center">{{__('resources.longtrip-routes.model.fields.event_day')}}</th>
        <th>{{ __('resources.shuttle-routes-boarding.name-plural') }}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($shuttleRoutes as $route)
        <tr>
            <td width="25%" class=""><a href="{{ route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $route]) }}">{{ $route->name }}</a></td>
            <td width="5%" class="text-center">{{ $route->capacity }}</td>
            <td width="10%" class="text-center day-show">{{ $route->getSalesDatesFormattedAttribute()}}
            </td>
            <td width="40%" class="">
                {!! $route->boardingLocationsList !!}
            </td>
            <td width="10%" class="text-center skip">
                @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.shuttle.createBoardingLocation', [$provider, $company, $offer, $route]) }}"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.shuttle-routes-boarding.create') }}">
                    <i class="icon-Bus-2"></i>
                    </a>
                <a href="{{ route('backend.providers.companies.offers.shuttle.destroyRoute', [$provider, $company, $offer, $route]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
