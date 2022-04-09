@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-10">
        <h3 class="text-themecolor">
            {{ $offer->package->extendedName }}
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
                : <a href="{{ route('backend.providers.companies.offers.edit', [$provider, $company, $offer]) }}">
                    {{ $offer->package->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.additionals.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="additionalsForm" method="post" action="{{ route('backend.providers.companies.offers.additional.storeItem', [$provider, $company, $offer]) }}" autocomplete="off">
            <input type="hidden" name="sale_coefficient_id" data-coefficient="{{$offer->saleCoefficient->value}}" value="{{$offer->saleCoefficient->id}}" />
                <input type="hidden" name="navigation" value="{{ old('navigation', 'itens') }}" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.additionals.info') }}
                </div>

                <div class="card-body row">
                    <div class="form-group col-md-8">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.name') }}</strong>
                        </label>
                        <div class="tab-content br-n pn">
                            <div id="name-pt-br" class="tab-pane active">
                                <input type="text" name="name[pt-br]" class="form-control" value="{{ old('name.pt-br') }}" required/>
                            </div>
                            <div id="name-en" class="tab-pane">
                                <input type="text" name="name[en]" class="form-control" value="{{ old('name.en') }}" required/>
                            </div>
                            <div id="name-es" class="tab-pane">
                                <input type="text" name="name[es]" class="form-control" value="{{ old('name.es') }}" required/>
                            </div>
                        </div>
                        <ul class="nav nav-pills m-t-10">
                            <li class="nav-item"><a href="#name-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                            <li class="nav-item"><a href="#name-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                            <li class="nav-item"><a href="#name-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                        </ul>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-control-label">
                            <strong>Grupo</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="additional_group_id">
                            @foreach ($additionalGroups as $additionalGroup)
                                <option value="{{ $additionalGroup->id }}" @if (old('additional_group_id') == $additionalGroup->id) selected @endif>{{ $additionalGroup->name }} - {{ $additionalGroup->internal_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5 @if($errors->has('fields.sale_dates')) has-danger @endif" styles="display:none;">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.date_use') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="select2 select2-multiple form-control" name="fields[sale_dates][]" style="width: 100%" multiple>
                            @foreach ($offer->packageBookablePeriod as $period)
                                <option value="{{ $period['date']->format('Y-m-d') }}" @if (in_array($period['date']->format('Y-m-d'), old('fields.sale_dates', []))) selected @endif>{{ $period['date']->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-7 @if($errors->has('type')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.offers.type') }} - {{ __('resources.offers.linked') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="select2 select2-multiple form-control" name="type[]" style="width: 100%" multiple>
                            <option value="{{ \App\Enums\OfferType::BUSTRIP }}" @if (in_array(\App\Enums\OfferType::BUSTRIP, old('type', []))) selected @endif>{{ __('resources.offers.model.types.bus-trip') }}</option>
                            <option value="{{ \App\Enums\OfferType::SHUTTLE }}" @if (in_array(\App\Enums\OfferType::SHUTTLE, old('type', []))) selected @endif>{{ __('resources.offers.model.types.shuttle') }}</option>
                            <option value="{{ \App\Enums\OfferType::LONGTRIP }}" @if (in_array(\App\Enums\OfferType::LONGTRIP, old('type', []))) selected @endif>{{ __('resources.offers.model.types.longtrip') }}</option>
                            <option value="{{ \App\Enums\OfferType::HOTEL }}" @if (in_array(\App\Enums\OfferType::HOTEL, old('type', []))) selected @endif>{{ __('resources.offers.model.types.hotel') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('price')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.price_net') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    {{ $offer->currency }}
                                </span>
                            </div>
                            <input type="text" class="form-control input-money" data-bookable-price name="price" value="{{ old('price') }}" inputmode="numeric" style="text-align: right;">
                        </div>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('price')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.sale_price') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    {{ $offer->currency }}
                                </span>
                            </div>
                            <span class="form-control receive-span sale-price" data-bookable-receive-price value="{{ old('price', 0) }}" />
                        </div>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('stock')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.stock') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="stock" value="{{ old('stock') }}" />
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('availability')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.view') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="availability">
                            <option value="public" @if (old('availability') == 'public') selected @endif>{{ __('resources.additionals.public') }}</option>
                            <option value="exclusive" @if (old('availability') == 'exclusive') selected @endif>{{ __('resources.additionals.exclusive') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 @if($errors->has('allowed_companies')) has-danger @endif allowed_companies_row" styles="display:none;">
                        <label class="form-control-label">
                            <strong>{{ __('resources.label.info_by_company') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="select2 select2-multiple form-control" name="allowed_companies[]" style="width: 100%" multiple>
                            <option value=""></option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @if (in_array($company->id, old('allowed_companies', $additionalItem->allowed_companies ?? []))) selected @endif>{{ $company->provider->name }} - {{ $company->company_name }}</option>
                            @endforeach
                        </select>
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
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/backend/js/resources/additionals.js"></script>
<script src="/backend/js/resources/pricing.hotel.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        handleAllowedCompanysRow(availabilitySelect.val());

    });
</script>
@endpush
