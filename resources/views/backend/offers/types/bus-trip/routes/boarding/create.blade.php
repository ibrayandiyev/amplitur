@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.bustrip-routes-boarding.create') }}</h3>
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
                <a href="{{ route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute]) }}">{{ $bustripRoute->name }}</a>
                 - {{ __('resources.bustrip-routes-boarding.create') }}
            </li>
        </ol>
    </div>
</div>

<form id="bustripBoardingLocationForm" method="post" action="{{ route('backend.providers.companies.offers.bustrip.storeBoardingLocation', [$provider, $company, $offer, $bustripRoute]) }}" autocomplete="off">
    @csrf
    <input type="hidden" name="sale_coefficient_id" data-coefficient="{{$offer->saleCoefficient->value}}" value="{{$offer->saleCoefficient->id}}" />
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.bustrip-routes-boarding.info') }}
        </div>

        <div class="card-body pricing-tab">
            <div class="row">
                <div class="form-group col-md-3 @if($errors->has('boarding_at')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.bustrip-routes-boarding.model.boarding_at') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control datetimepicker" name="boarding_at" value="{{ old('boarding_at') }}" />
                </div>
                <div class="form-group col-md-3 @if($errors->has('travel_time')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.bustrip-routes-boarding.model.travel_time') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" class="form-control" name="travel_time" value="{{ old('travel_time', 60) }}" />
                </div>
                <div class="form-group col-md-3 @if($errors->has('price')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.bustrip-routes-boarding.model.net_sale') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                {{ $offer->currency }}
                            </span>
                        </div>
                        <input type="text" class="form-control input-money" data-bookable-price name="price" value="{{ old('price') }}" />
                    </div>
                </div>
                <div class="form-group col-md-3 @if($errors->has('price')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.bustrip-routes-boarding.model.sale_price') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                {{ $offer->currency }}
                            </span>
                        </div>
                        <span class="form-control receive-span sale-price" data-bookable-receive-price value="{{ old('price', 0) }}" />

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.country') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="address[country]" >
                        <option value>{{ __('messages.select') }}</option>
                        @foreach ($countries as $country)
                        <option value="{{ $country->iso2 }}" @if(old('address.country') == $country->iso2) selected @endif>{{ country($country) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" data-state-region>
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.state') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="address[state]" disabled></select>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                    <label class="form-control-label">
                        <strong>{{ __('resources.events.model.city') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" name="address[city]" disabled></select>
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.zip') }}</strong>
                    </label>
                    <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip') }}">
                </div>
                <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.address') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control " name="address[address]" value="{{ old('address.address') }}">
                </div>
                <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.number') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[number]" value="{{ old('address.number') }}">
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.neighborhood') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood') }}">
                </div>
                <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                    <label class="form-control-label">
                        <strong>{{ __('resources.address.complement') }}</strong>
                    </label>
                    <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement') }}">
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="address[latitude]" value="{{ old('address.latitude') }}" />
                <input type="hidden" name="address[longitude]" value="{{ old('address.longitude') }}" />
                <div class="col-md-12 map-container" id="map"></div>
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
    <script type="text/javascript">
        fillAddress({
            country: "{{ old('address.country') }}",
            state: "{{ old('address.state') }}",
            city: "{{ old('address.city') }}"
        });
    </script>
@endpush
