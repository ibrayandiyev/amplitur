@include("backend.offers.types.longtrip.routes.accommodations.quick_add")
@if (count($longtripRoute->longtripAccommodations) > 0)
<table id="longtripAccommodationsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <th>{{ __('resources.hotels.name') }}</th>
        <th class="text-center">{{ __('resources.longtrip-accommodations.model.checkin') }}</th>
        <th class="text-center">{{ __('resources.longtrip-accommodations.model.checkout') }}</th>
        <th class="text-center">{{ __('resources.label.place_stay_night') }}</th>
        <th width="10%" class="text-center">{{ __('messages.actions') }}</th>
    </thead>
    <tbody>
        @foreach ($longtripRoute->longtripAccommodations->load("type")->sortBy("type.name") as $key => $longtripAccommodation)
            <tr class="row-key"><th colspan='3'>{{ $longtripAccommodation->type->name }}<th>
            <th class="text-center">
                @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.longtrip.createLongtripAccommodationHotel', [$provider, $company, $offer, $longtripRoute, $longtripAccommodation]) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.longtrip-accommodations.create') }}">
                    <i class="fa fa-building-o"></i>
                </a>
                <a href="{{ route('backend.providers.companies.offers.longtrip.accommodation-type.destroyLongtripAccommodation', [$provider, $company, $offer, $longtripRoute, $longtripAccommodation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
                @endif
            </th></tr>
            @if($longtripAccommodation->longtripAccommodationHotel())
                @foreach ($longtripAccommodation->longtripAccommodationHotel()->orderBy("checkin")->get() as $longtripAccommodationHotel)
                    <tr data-href="">
                        <td class="">
                            <input type='hidden' name='quick[{{ $longtripAccommodationHotel->id }}][accommodation_name]' value='{{ $longtripAccommodation->type->name }}'>
                            @include("backend.offers.types.longtrip.routes.accommodations.quick_edit_hotel")
                        </td>
                        <td class=" text-center">
                        @php
                            $checkin    = (old('quick.'.$longtripAccommodationHotel->id.'.checkin'))? old('quick.'.$longtripAccommodationHotel->id.'.checkin'):$longtripAccommodationHotel->getFriendlyCheckin();
                            $checkout   = (old('quick.'.$longtripAccommodationHotel->id.'.checkout'))? old('quick.'.$longtripAccommodationHotel->id.'.checkout'):$longtripAccommodationHotel->getFriendlyCheckout();
                        @endphp
                            <input type='text' name='quick[{{ $longtripAccommodationHotel->id }}][checkin]' maxlength="10" class="form-control datepicker" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-inputmask-placeholder="dd/mm/aaaa" placeholder="__/__/____" data-date='{{ ($longtripAccommodationHotel->checkin!=null)?$longtripAccommodationHotel->checkin->format('Y-m-d'):null }}' value='{{ $checkin }}' {{$readonly}}>
                        </td>
                        <td class=" text-center">
                            <input type='text' name='quick[{{ $longtripAccommodationHotel->id }}][checkout]' maxlength="10" class="form-control datepicker" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-inputmask-placeholder="dd/mm/aaaa"  placeholder="__/__/____" data-date='{{ ($longtripAccommodationHotel->checkout!= null)?$longtripAccommodationHotel->checkout->format('Y-m-d'):null }}' value='{{ $checkout }}' {{$readonly}}>
                        </td>
                        <td class=" text-center">
                            <select name="quick[{{ $longtripAccommodationHotel->id }}][longtrip_hotel_label_id]" class="select2">
                                <option value="" @if($longtripAccommodationHotel->longtrip_hotel_label_id == null) selected="selected" @endif>{{ __('resources.options.default_hotel') }}</option>
                                @foreach ($longtripHotelLabels as $longtripHotelLabel)
                                    <option value="{{ $longtripHotelLabel->id }}" @if($longtripAccommodationHotel->longtrip_hotel_label_id == $longtripHotelLabel->id) selected="selected" @endif>{{ $longtripHotelLabel->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center skip">
                            @if ($canChange)
                                <a href="{{ route('backend.providers.companies.offers.longtrip.destroyLongtripAccommodationHotel', [$provider, $company, $offer, $longtripRoute, $longtripAccommodation, $longtripAccommodationHotel]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
@else
    <div class="row m-b-10">
        <div class="col-md-12">
            Nenhuma acomodação cadastrada
        </div>
    </div>
@endif
@push('styles')
    <link rel="stylesheet" href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" />
@endpush
@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            defaultViewDate: {year:'{{ $longtripRoute->offer->package->starts_at->format("Y") }}', month:'{{ $longtripRoute->offer->package->starts_at->format("m") }}', day:'{{ $longtripRoute->offer->package->starts_at->format("d") }}'},
            clearBtn: true,
            todayHighlight: true,
            startDate: "{{$longtripRoute->offer->package->starts_at->sub(30, 'days')->format("d-m-Y")}}",
            endDate: "{{$longtripRoute->offer->package->starts_at->add(30, 'days')->format("d-m-Y")}}",
        });
        $('.datepicker').inputmask();
    </script>
@endpush

