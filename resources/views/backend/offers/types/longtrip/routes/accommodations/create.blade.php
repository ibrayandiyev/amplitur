@php
    $canChange = !(user()->isProvider() && $offer->hasBookings());
    $readonly = $canChange ? null : 'readonly disabled';
@endphp
@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.longtrip-accommodations.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.edit', [$provider, $company]) }}">{{ $company->company_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}">{{ __('resources.offers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.edit', [$provider, $company, $offer]) }}">{{__('resources.'. $offer->type .'.name')}} - {{ $offer->package->extendedName }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute]) }}">{{ $longtripRoute->name }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.longtrip-accommodations.create') }}</li>
        </ol>
    </div>
</div>

<form id="longtripAccomodationForm" method="post" action="{{ route('backend.providers.companies.offers.longtrip.storeLongtripAccommodationHotel', [$provider, $company, $offer, $longtripRoute]) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.longtrip-accommodations.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.longtrip-accommodations.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('longtrip_accommodation_type_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-accommodations.model.type') }}</strong>
                                    <span class="text-danger">*</span>:
                                </label>
                                <label class="form-control-label">
                                    {{$longtripAccommodation->type->name}}
                                    <input type='hidden' name='longtrip_accommodation_id'         value='{{ $longtripAccommodation->id }}' />
                                    <input type='hidden' name='longtrip_accommodation_type_id'    value='{{ $longtripAccommodation->type->id }}' />
                                </label>
                            </div>
                        </div>
                        <div class="row" id="hotel-details">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.exist_hotel') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="hotel_id"
                                    class="select2 m-b-10"
                                    style="width: 100%">
                                    <option value="">Find one or fill the fields below</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id }}" @if(old('hotel_id') == $hotel->id) selected="selected" @endif>{{ $hotel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="name" class="form-control " value="{{ old('name') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.country') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[country]" {{ $readonly }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->iso2 }}" @if ($country->iso2 == old('address.country')) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($canChange)
                                    <select class="form-control" name="address[state]" {{ $readonly }} disabled></select>
                                @else
                                    <input type="text" class="form-control" name="address[state-readonly]" value="{{ state(old('address.country'), old('address.state')) }}" {{ $readonly }} />
                                @endif
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($canChange)
                                    <select class="form-control" name="address[city]" {{ $readonly }} disabled></select>
                                @else
                                    <input type="text" class="form-control" name="address[city-readonly]" value="{{ city(old('address.city')) }}" {{ $readonly }} />
                                @endif
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', '') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', '') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', '') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', '') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', '') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('hotel.checkin')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkin') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkin]" type="time" class="form-control" value="{{ old('hotel.checkin') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('hotel.checkout')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkout') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkout]" type="time" class="form-control" value="{{ old('hotel.checkout') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('checkin')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-accommodations.model.checkin') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="checkin" type="text" maxlength="10" class="form-control datepicker" value="{{ old('checkin') }}" placeholder="__/__/_____" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('checkout')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-accommodations.model.checkout') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="checkout" type="text" maxlength="10" class="form-control datepicker" value="{{ old('checkout') }}" placeholder="__/__/_____" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary save">
                <i class="fa fa-save"></i> {{ __('messages.save') }}
            </button>
            <a href="{{ route('backend.providers.companies.offers.edit', [$provider, $company, $offer]) }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>
        </div>
    </div>
</form>
@endsection

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        fillAddress({
            country: "{{ old('address.country') }}",
            state: "{{ old('address.state') }}",
            city: "{{ old('address.city') }}"
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
    </script>
    <script>
        $(document).ready(function () {
            let select = $('select[name="hotel_id"]');

            select.select2({
                tags: true,
                createTag: function (params) {
                    var term = $.trim(params.term);

                    if (term === '') {
                        return null;
                    }

                    return {
                        id: -1,
                        text: '* ' + term,
                    }
                }
            });

            select.on('change', async function (e) {
                let hotelId = select.val();
                let option = $('select[name="hotel_id"] option:selected');
                let hotel = option.data();

                $('#hotel-details').show();

                if (hotelId == -1) {
                    $('input[name="hotel[name]"]').val(option.text().replace('* ', ''));
                } else {
                    $('input[name="hotel[name]"]').val(hotel.hotelName);
                    $('select[name="address[country]"').val(hotel.hotelAddressCountry);

                    await fillAddress({
                        country: hotel.hotelAddressCountry,
                        state: hotel.hotelAddressState,
                        city: hotel.hotelAddressCity
                    });

                    $('input[name="address[zip]"]').val(hotel.hotelAddressZip);
                    $('input[name="address[address]"]').val(hotel.hotelAddressAddress);
                    $('input[name="address[number]"]').val(hotel.hotelAddressNumber);
                    $('input[name="address[neighborhood]"]').val(hotel.hotelAddressNeighborhood);
                    $('input[name="address[complement]"]').val(hotel.hotelAddressComplement);
                    $('input[name="address[latitude]"]').val(hotel.hotelAddressLatitude);
                    $('input[name="address[longitude]"]').val(hotel.hotelAddressLongitude);
                    $('select[name="hotel[category_id]"]').val(hotel.hotelCategoryId);

                    initMap();
                }
            });
        });
    </script>
@endpush
