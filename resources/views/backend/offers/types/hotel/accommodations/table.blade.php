@if (count($hotelAccommodations) > 0)
<table id="routesTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <th>{{ __('resources.hotel-accommodations.model.type') }}</th>
        <th width="50%">{{ __('resources.hotel-accommodations.model.inclusions') }}</th>
        <th width="10%" class="text-center">{{ __('messages.actions') }}</th>
    </thead>
    <tbody>
        @foreach ($hotelAccommodations->load("type")->sortBy("type.name") as $hotelAccommodation)
        <tr>
            <td class=""><a href="{{ route('backend.providers.companies.offers.hotel.editHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation]) }}">{{ $hotelAccommodation->typeLabel }}</a></td>
            <td class="">{!! $hotelAccommodation->structuresLabel !!}</td>
            <td class="text-center skip">
                @if ($canChange)
                    <a href="{{ route('backend.providers.companies.offers.hotel.destroyHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            {{__('messages.offer.no_accommodation')}}
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
