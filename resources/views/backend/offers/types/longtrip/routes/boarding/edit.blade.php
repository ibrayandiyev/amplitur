@php
    $address = $longtripBoardingLocation->address;
    $canChange = !(user()->isProvider() && $offer->hasBookings());
    $disabled = $canChange ? null : 'disabled';
    $readonly = $canChange ? null : 'readonly';
@endphp
@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">
            {{ $longtripRoute->name }} //
            {{ __('resources.longtrip-routes-boarding.edit') }}
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
                 : <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }}</a>
                 : <a href="{{ route('backend.providers.companies.edit', [$provider, $company]) }}">{{ $company->company_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}">{{ __('resources.offers.name-plural') }}</a>
                 : <a href="{{ route('backend.providers.companies.offers.edit', [$provider, $company, $offer]) }}">{{__('resources.'. $offer->type .'.name')}} - {{ $offer->package->extendedName }}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute]) }}">{{ $longtripRoute->name }}</a>
                 : {{ __('resources.longtrip-routes-boarding.name') }} - {{ $longtripBoardingLocation->extendedName }}
            </li>
        </ol>
    </div>
</div>

<form id="longtripBoardingLocationForm" method="post" action="{{ route('backend.providers.companies.offers.longtrip.updateBoardingLocation', [$provider, $company, $offer, $longtripRoute, $longtripBoardingLocation]) }}" autocomplete="off">
    @csrf
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="sale_coefficient_id" data-coefficient="{{$offer->saleCoefficient->value}}" value="{{$offer->saleCoefficient->id}}" />
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.longtrip.info') }} - {{ __('resources.longtrip-routes-boarding.info') }}
        </div>

        <div class="card-body pricing-tab">
            <div class="row">
                <div class="form-group col-md-3 @if($errors->has('boarding_at')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.longtrip-routes-boarding.model.boarding_at') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" maxlength="17" class="form-control datetimepicker" name="boarding_at" value="{{ old('boarding_at', $longtripBoardingLocation->boardingAtLocal) }}" {{ $readonly }} />
                </div>
                <div class="form-group col-md-3 @if($errors->has('ends_at')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.longtrip-routes-boarding.model.ends_at') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" maxlength="17" class="form-control  datetimepicker" name="ends_at" value="{{ old('ends_at', $longtripBoardingLocation->endsAtLocal) }}" {{ $readonly }} />
                </div>
                <div class="form-group col-md-2 @if($errors->has('price')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.longtrip-routes-boarding.model.net_sale') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                {{ $offer->currency }}
                            </span>
                        </div>
                        <input type="text" class="form-control input-money" data-bookable-price name="price" value="{{ old('price', moneyDecimal($longtripBoardingLocation->price)) }}" />
                    </div>
                </div>
                <div class="form-group col-md-2 @if($errors->has('price')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.longtrip-routes-boarding.model.sale_price') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                {{ $offer->currency }}
                            </span>
                        </div>
                        <span class="form-control receive-span sale-price" data-bookable-receive-price name="price" value="{{ old('price', money($longtripBoardingLocation->price)) }}" /></span>
                    </div>
                </div>
                <div class="form-group col-md-2 @if($errors->has('is_available')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.longtrip-routes-boarding.model.exhaust-availability') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="is_available">
                        <option value="0" @if(old('is_available', $longtripBoardingLocation->is_available) == 0) selected @endif>{{ __('messages.no') }}</option>
                        <option value="1" @if(old('is_available', $longtripBoardingLocation->is_available) == 1) selected @endif>{{ __('messages.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.country') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="address[country]" {{ $disabled }}>
                        <option value>{{ __('messages.select') }}</option>
                        @foreach ($countries as $country)
                        <option value="{{ $country->iso2 }}" @if ($address->country == $country->iso2) selected @endif>{{ country($country) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.state') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    @if ($canChange)
                        <select class="form-control" name="address[state]" {{ $readonly }}></select>
                    @else
                        <input type="text" class="form-control" value="{{ state($address->country, $address->state) }}" {{ $readonly }} />
                    @endif
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.city') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    @if ($canChange)
                        <select class="form-control" name="address[city]" {{ $readonly }}></select>
                    @else
                        <input type="text" class="form-control" value="{{ city($address->city) }}" {{ $readonly }} />
                    @endif
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.zip') }}</strong>
                    </label>
                    <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', $address->zip) }}" {{ $readonly }}>
                </div>
                <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.address') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', $address->address) }}" {{ $readonly }}>
                </div>
                <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.number') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', $address->number) }}" {{ $readonly }}>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.neighborhood') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', $address->neighborhood) }}" {{ $readonly }}>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.complement') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', $address->complement) }}" {{ $readonly }}>
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="address[latitude]" value="{{ old('address.latitude', (float) $longtripBoardingLocation->address->latitude) }}" {{ $readonly }}>
                <input type="hidden" name="address[longitude]" value="{{ old('address.longitude', (float) $longtripBoardingLocation->address->longitude) }}" {{ $readonly }}>
                <div class="col-md-12 map-container" id="map"></div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary save">
                <i class="fa fa-save"></i> {{ __('messages.save') }}
            </button>
            <a href="{{ route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute]) }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>
            @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.longtrip.destroyBoardingLocation', [$provider, $company, $offer, $longtripRoute, $longtripBoardingLocation]) }}"  class="btn btn-danger delete pull-right">
                    <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                </a>
            @endif
        </div>
    </div>
</form>
@endsection

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
    <script src="/backend/js/resources/pricing.hotel.js"></script>
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            mask: true
        });
    </script>
    @if ($canChange)
        <script type="text/javascript">
            fillAddress({
                country: "{{ old('address.country', $address->country) }}",
                state: "{{ old('address.state', $address->state) }}",
                city: "{{ old('address.city', $address->city) }}"
            });
    </script>
    @endif
@endpush
