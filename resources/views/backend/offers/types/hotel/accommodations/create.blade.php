@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.hotel-accommodations.create') }}</h3>
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
                : <a href="{{ route('backend.providers.companies.offers.edit', [$provider, $company, $offer]) }}"> - {{ $offer->package->extendedName }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.hotel-accommodations.create') }}</li>
        </ol>
    </div>
</div>

<form id="hotelAccomodationForm" method="post" action="{{ route('backend.providers.companies.offers.hotel.storeHotelAccommodation', [$provider, $company, $offer]) }}" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.hotel-accommodations.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.hotel-accommodations.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('hotel_accommodation_type_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotel-accommodations.model.type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="hotel_accommodation_type_id" class="select2 m-b-10" style="width: 100%">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}" @if(old('hotel_accommodation_type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('hotel_accommodation_structures')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotel-accommodations.model.hotel_accommodation_structures') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select id='hotelStructure' name="hotel_accommodation_structures[]" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple>
                                    @foreach ($hotelAccommodationStructures as $hotelAccommodationStructure)
                                        <option value="{{ $hotelAccommodationStructure->id }}">{{ $hotelAccommodationStructure->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('inclusions')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.inclusions') }}</strong>
                                </label>
                                <select id='inclusions' name="inclusions[]" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple">
                                    @foreach ($inclusions as $inclusion)
                                        <option value="{{ $inclusion->id }}">{{ $inclusion->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (user()->canManageOfferExtras($offer))
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.shuttle-routes.model.extra-inclusions') }}</strong>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="inclusions-extra-inclusions-pt-br" class="tab-pane active">
                                            <textarea class="form-control summernote" name="extra_inclusions[pt-br]" placeholder="pt-br">{!! old('extra_inclusions.pt-br') !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-inclusions-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_inclusions[en]" placeholder="en">{!! old('extra_inclusions.en') !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-inclusions-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_inclusions[es]" placeholder="es">{!! old('extra_inclusions.es') !!}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30">
                                        <li class="nav-item"><a href="#inclusions-extra-inclusions-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#inclusions-extra-inclusions-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#inclusions-extra-inclusions-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('exclusions')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.exclusions') }}</strong>
                                </label>
                                <select id="exclusions" name="exclusions[]" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple">
                                    @foreach ($exclusions as $exclusion)
                                        <option value="{{ $exclusion->id }}">{{ $exclusion->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (user()->canManageOfferExtras($offer))
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.shuttle-routes.model.extra-exclusions') }}</strong>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="inclusions-extra-exclusions-pt-br" class="tab-pane active">
                                            <textarea class="form-control summernote" name="extra_exclusions[pt-br]" placeholder="pt-br">{!! old('extra_exclusions.pt-br') !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-exclusions-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_exclusions[en]" placeholder="en">{!! old('extra_exclusions.en') !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-exclusions-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_exclusions[es]" placeholder="es">{!! old('extra_exclusions.es') !!}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30">
                                        <li class="nav-item"><a href="#inclusions-extra-exclusions-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#inclusions-extra-exclusions-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#inclusions-extra-exclusions-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
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
