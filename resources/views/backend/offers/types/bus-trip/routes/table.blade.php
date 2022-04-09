@php
    $bustripRoutes = $offer->bustripRoutes;
    $bustripRoutes = $bustripRoutes->sortBy("name");
@endphp

@if (count($bustripRoutes) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.bustrip-routes.model.name') }}</th>
        <th class="text-center">{{ __('resources.bustrip-routes.model.capacity') }}</th>
        <th class="text-center">{{__('resources.longtrip-routes.model.fields.event_day')}}</th>
        <th>{{ __('resources.bustrip-routes-boarding.name-plural') }}</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($bustripRoutes as $route)
        <tr>
            <td width="25%" class=""><a href="{{ route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $route]) }}">{{ $route->name }}</a></td>
            <td width="5%" class="text-center">{{ $route->capacity }}</td>
            <td width="13%" class="text-center day-show">{{ $route->getSalesDatesFormattedAttribute()}}</td>
            <td class="">{!! $route->boardingLocationsList !!}</td>
            <td width="7%" class="text-center skip">

            @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.bustrip.createBoardingLocation', [$provider, $company, $offer, $route]) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.bustrip-routes-boarding.create') }}">
                    <i class="icon-Bus-2"></i>

                </a>
                <a href="{{ route('backend.providers.companies.offers.bustrip.destroyRoute', [$provider, $company, $offer, $route]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
