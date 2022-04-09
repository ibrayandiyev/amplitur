@extends('backend.template.default')
@section('content')
@php
$readonly = 0;
@endphp
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.hotels.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.hotels.index') }}">{{ __('resources.hotels.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.hotels.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.hotels.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.hotels.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="eventForm" method="post" action="{{ route('backend.hotels.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.hotels.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#seo" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">Images (Coming Up)</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">

                    <div class="tab-pane active" id="hotel-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-6 @if($errors->has('hotel.name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[name]" class="form-control " value="{{ old('hotel.name') }}"  {{ $readonly }} />
                                <input type="hidden" name="hotel[registry_type]" value="{{ App\Enums\OfferType::LONGTRIP }}"  {{ $readonly }} />
                            </div>

                        @if (auth()->user()->isMaster())

                            <div class="form-group col-md-6 @if($errors->has('hotel.provider_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.provider') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control " name="hotel[provider_id]"  {{ $readonly }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}" @if(old('hotel.provider_id') == $provider->id) selected @endif>{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.country') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[country]"  {{ $readonly }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if(old('address.country') == $country->iso2) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[state]" {{ $readonly }} disabled></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[city]" {{ $readonly }} disabled></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement') }}" {{ $readonly }}>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude') }}" {{ $readonly }} />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude') }}" {{ $readonly }} />
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('hotel.category_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.category') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control " name="hotel[category_id]" {{ $readonly }}>
                                    @foreach ($hotelCategories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == old('hotel.category_id')) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('hotel.checkin')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkin') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkin]" type="time" class="form-control" value="{{ old('hotel.checkin') }}" {{ $readonly }} />
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('hotel.checkout')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkout') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkout]" type="time" class="form-control" value="{{ old('hotel.checkout') }}" {{ $readonly }} />
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('hotel.inclusions')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.inclusions') }}</strong>
                                </label>
                                <select name="hotel[hotel_structures][]" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" {{ $readonly }}>
                                    @foreach ($hotelStructures as $hotelStructure)
                                        <option value="{{ $hotelStructure->id }}" >{{ $hotelStructure->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('observations')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-routes.model.observations') }}</strong>
                                </label>
                                <select name="hotel[observations][]" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" {{ $readonly }}>
                                    @foreach ($observations as $observation)
                                        <option value="{{ $observation->id }}" >{{ $observation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        -->
                        <div class="row">
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude') }}" />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude') }}" />
                            <div class="col-md-12 map-container" id="map"></div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.hotels.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
    <link rel="stylesheet" href="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"  />
@endpush

@push('scripts')
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script src="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>

    <script type="text/javascript">
        fillAddress({
            country: "{{ old('address.country') }}",
            state: "{{ old('address.state') }}",
            city: "{{ old('address.city') }}"
        });

        $('.summernote').summernote({
            height: 350,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    </script>
@endpush
