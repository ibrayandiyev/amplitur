@extends('backend.template.default')

@php
    $queryString = '?';
    $queryString .= !empty($offerType) ? '&offerType=' . $offerType : '';
    $queryString .= !empty($provider) ? '&provider_id=' . $provider->id : '';
    $queryString .= !empty($event) ? '&event_id=' . $event->id : '';
    $queryString .= !empty($company) ? '&company_id=' . $company->id : '';
@endphp

@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.packages.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.packages.index') }}">
                    {{ __('resources.packages.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.packages.edit') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.packages.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.packages.create') }}
            </a>
        </div>
    </div>
</div>

@php

$readonly = $disabled = "";
@endphp
@if (user()->canManagePackageDetails() )
<form id="packageForm" method="post" action="{{ route('backend.packages.update', $package) }}{{$queryString}}" autocomplete="off">

    @csrf
    <input type="hidden" name="_method" value="put">
    @else
    @php
        $readonly   = "readonly";
        $disabled   = "disabled";
        $countries  = $countries->where("iso2", "=", $package->address->country);
    @endphp
@endif
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.packages.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bus-trip.basic-info') }}</span>
                        </a>
                    </li>

                    @if (user()->canSeePackageSeo())
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#seo" role="tab">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">SEO</span>
                            </a>
                        </li>
                    @endif

                    @if (user()->canSeePackageOffers())
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#offers" role="tab">
                                <span class="hidden-sm-up"><i class="ti-folder"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.offers.name-plural') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (user()->canSeePackagePaymentMethods())
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#payment-methods" role="tab">
                                <span class="hidden-sm-up"><i class="ti-dollar"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.payment-methods.name-plural') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tab-panel">
                        <div class="row">
                            @if (!empty($event))
                                <div class="form-group col-md-6 @if($errors->has('event_id')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.name') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="text" class="form-control " name="event" value="{{ $event->name }}" readonly>
                                </div>

                                @if (user()->canManagePackageDetails())
                                    <div class="form-group col-md-3 @if($errors->has('display_type')) has-danger @endif">
                                        <label class="form-control-label">
                                            <strong>{{ __('resources.events.model.display_type') }}</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" name="display_type">
                                            <option value="{{ \App\Enums\DisplayType::PUBLIC }}" @if (old('display_type', $package->display_type) == \App\Enums\DisplayType::PUBLIC) selected @endif>{{ __('resources.events.model.display_types.public') }}</option>
                                            <option value="{{ \App\Enums\DisplayType::NON_LISTED }}" @if (old('display_type', $package->display_type) == \App\Enums\DisplayType::NON_LISTED) selected @endif>{{ __('resources.events.model.display_types.non_listed') }}</option>
                                        </select>
                                        @if ($package->isNonListed() && $package->token != null)
                                            <span class="help-block m-t-10">
                                                <a href="{{ route(getRouteByLanguage('frontend.packages.exclusive.show'), ['token' => $package->token]) }}" target="_blank">{{ $package->token }} <i class="fa fa-external-link"></i></a>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="form-group col-md-6 @if($errors->has('event_id')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.name') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="event_id" class="form-control  select2">
                                        <option value="">{{ __('messages.select') }}</option>
                                        <option value="{{ $event->id }}" data-duration="{{ $event->getDuration() }}">{{ $event->name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 @if($errors->has('display_type')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.model.display_type') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="display_type">
                                        <option value="{{ \App\Enums\DisplayType::PUBLIC }}" @if (old('display_type', $package->display_type) == \App\Enums\DisplayType::PUBLIC) selected @endif>{{ __('resources.events.model.display_types.public') }}</option>
                                        <option value="{{ \App\Enums\DisplayType::NON_LISTED }}" @if (old('display_type', $package->display_type) == \App\Enums\DisplayType::NON_LISTED) selected @endif>{{ __('resources.events.model.display_types.non_listed') }}</option>
                                    </select>
                                    @if (!$package->isPublic())
                                        <span class="help-block m-t-10">
                                            <a href="#">{{ $package->token }} <i class="fa fa-external-link"></i></a>
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="form-group col-md-6 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.packages.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="name" value="{{ $package->name }}">
                            </div>

                            @if (user()->canManagePackageDetails())
                                <div class="form-group col-md-3 @if($errors->has('status')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.packages.model.status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="status">
                                        <option value="{{ \App\Enums\ProcessStatus::IN_ANALYSIS }}" @if (old('status', $package->status) == \App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::IN_ANALYSIS) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::ACTIVE }}" @if (old('status', $package->status) == \App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::ACTIVE) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::REFUSED }}" @if (old('status', $package->status) == \App\Enums\ProcessStatus::REFUSED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::REFUSED) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::SUSPENDED }}" @if (old('status', $package->status) == \App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::SUSPENDED) }}</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('starts_at')) has-danger @endif" data-one-day-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.starts_at') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="17" class="form-control datetimepicker" name="starts_at" value="{{ old('starts_at', $package->startsAtLocal) }}" placeholder="__/__/____, 00:00" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('ends_at')) has-danger @endif" data-range-date-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.ends_at') }}</strong>
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" maxlength="17" class="form-control datetimepicker" name="ends_at" value="{{ old('ends_at', $package->endsAtLocal) }}" placeholder="__/__/____, 00:00" {{ $readonly }}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.country') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[country]" {{ $readonly }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if($country->iso2 == old('address.country', $package->address->country)) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[state]" disabled {{ $readonly }}></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[city]" disabled {{ $readonly }}></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', $package->address->zip) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', $package->address->address) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }} </strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', $package->address->number) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }} </strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', $package->address->neighborhood) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', $package->address->complement) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-8 @if($errors->has('location')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.location') }}</strong>
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="url" class="form-control " name="location" data-map-location value="{{ old('location', $package->location) }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('website')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.website') }}</strong>
                                </label>
                                <input type="url" class="form-control text-lowercase" name="website" value="{{ old('website', $package->website) }}" {{ $readonly }}>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude', (float) $package->address->latitude) }}" />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude', (float) $package->address->longitude) }}" />
                            <div class="col-md-12 map-container" id="map"></div>
                        </div>
                    </div>

                    @if (user()->canSeePackageSeo())
                        <div class="tab-pane" id="seo" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-12 @if($errors->has('meta_keywords')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.model.meta-keywords') }}</strong>
                                    </label>

                                    <div class="tab-content br-n pn">
                                        <div id="seo-meta-keywords-pt-br" class="tab-pane active">
                                            <div class="tags-default">
                                            @php
                                            $old = old('meta_keywords');
                                            if($old != ""){
                                                $data = $old['pt-br'];
                                            }else{
                                                $data = $package->getTranslation('meta_keywords', 'pt-br');
                                            }
                                            @endphp
                                                <input type="text" name="meta_keywords[pt-br]" class="form-control" data-role="tagsinput" value="{{  $data }}"/>
                                                <span class="help-block">
                                                    <small>{{ __('resources.events.hints.keywords') }}</small>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="seo-meta-keywords-en" class="tab-pane">
                                            <div class="tags-default">
                                            @php
                                            $old = old('meta_keywords');
                                            if($old != ""){
                                                $data = $old['en'];
                                            }else{
                                                $data = $package->getTranslation('meta_keywords', 'en');
                                            }
                                            @endphp
                                                <input type="text" name="meta_keywords[en]" class="form-control" data-role="tagsinput" value="{{ $data }}"/>
                                                <span class="help-block">
                                                    <small>{{ __('resources.events.hints.keywords') }}</small>
                                                </span>
                                            </div>
                                        </div>
                                        <div id="seo-meta-keywords-es" class="tab-pane">
                                            <div class="tags-default">
                                            @php
                                            $old = old('meta_keywords');
                                            if($old != ""){
                                                $data = $old['es'];
                                            }else{
                                                $data = $package->getTranslation('meta_keywords', 'es');
                                            }
                                            @endphp
                                                <input type="text" name="meta_keywords[es]" class="form-control" data-role="tagsinput" value="{{ $data }}"/>
                                                <span class="help-block">
                                                    <small>{{ __('resources.events.hints.keywords') }}</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30 m-t-10">
                                        <li class="nav-item active"><a href="#seo-meta-keywords-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#seo-meta-keywords-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#seo-meta-keywords-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 @if($errors->has('meta_description')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.model.meta-description') }}</strong>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="seo-meta-description-pt-br" class="tab-pane active">
                                            @php
                                            $old = old('meta_description');
                                            if($old != ""){
                                                $data = $old['pt-br'];
                                            }else{
                                                $data = $package->getTranslation('meta_description', 'pt-br');
                                            }
                                            @endphp
                                            <textarea name="meta_description[pt-br]" class="form-control" rows="5">{{ $data }}</textarea>
                                        </div>
                                        <div id="seo-meta-description-en" class="tab-pane">
                                            @php
                                            $old = old('meta_description');
                                            if($old != ""){
                                                $data = $old['en'];
                                            }else{
                                                $data = $package->getTranslation('meta_description', 'en');
                                            }
                                            @endphp
                                            <textarea name="meta_description[en]" class="form-control" rows="5">{{ $data }}</textarea>
                                        </div>
                                        <div id="seo-meta-description-es" class="tab-pane">
                                            @php
                                            $old = old('meta_description');
                                            if($old != ""){
                                                $data = $old['es'];
                                            }else{
                                                $data = $package->getTranslation('meta_description', 'es');
                                            }
                                            @endphp
                                            <textarea name="meta_description[es]" class="form-control" rows="5">{{ $data }}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30 m-t-10">
                                        <li class="nav-item active"><a href="#seo-meta-description-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#seo-meta-description-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#seo-meta-description-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 @if($errors->has('description')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.model.description') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="seo-description-pt-br" class="tab-pane active">
                                            @php
                                            $old = old('description');
                                            if($old != ""){
                                                $data = $old['pt-br'];
                                            }else{
                                                $data = $package->getTranslation('description', 'pt-br');
                                            }
                                            @endphp
                                            <textarea class="form-control summernote" name="description[pt-br]" rows="8">{!! $data !!}</textarea>
                                        </div>
                                        <div id="seo-description-en" class="tab-pane">
                                            @php
                                            $old = old('description');
                                            if($old != ""){
                                                $data = $old['en'];
                                            }else{
                                                $data = $package->getTranslation('description', 'en');
                                            }
                                            @endphp
                                            <textarea class="form-control summernote" name="description[en]" rows="8">{!! $data !!}</textarea>
                                        </div>
                                        <div id="seo-description-es" class="tab-pane">
                                            @php
                                            $old = old('description');
                                            if($old != ""){
                                                $data = $old['es'];
                                            }else{
                                                $data = $package->getTranslation('description', 'es');
                                            }
                                            @endphp
                                            <textarea class="form-control summernote" name="description[es]" rows="8">{!! $data !!}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30 m-t-10">
                                        <li class="nav-item active"><a href="#seo-description-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#seo-description-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#seo-description-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (user()->canSeePackageOffers())
                        <div class="tab-pane" id="offers" role="tabpanel">
                            @include('backend.offers.table')
                        </div>
                    @endif

                    @if (user()->canSeePackagePaymentMethods())
                        <div class="tab-pane" id="payment-methods" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{__('resources.packages.payment_table.deadline')}}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="payment_expire_days" value="{{ $package->payment_expire_days }}" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                {{__('resources.packages.payment_table.days')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('backend.packages.createPaymentMethod', $package) }}" class="btn btn-primary btn-sm pull-right mb-10">
                                        {{ __('messages.add-item') }}
                                    </a>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <th class="text-center" width="20%">{{__('resources.packages.payment_table.payment_method')}}</th>
                                            <th class="text-center" width="15%">{{__('resources.packages.payment_table.gateway')}}</th>
                                            <th>{{__('resources.packages.payment_table.fee')}}</th>
                                            <th>{{__('resources.packages.payment_table.descount')}}</th>
                                            <th class="text-center" width="10%">{{__('resources.packages.payment_table.limit')}}</th>
                                            <th class="text-center" width="15%">{{__('resources.packages.payment_table.st_upfront')}}</th>
                                            <th class="text-center" width="10%">{{__('resources.packages.payment_table.installments')}}</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr class="table-success font-bold">
                                                <td colspan="8">{{__('resources.packages.payment_table.int')}}</td>
                                            </tr>
                                            @foreach ($paymentMethods['international'] as $paymentMethod)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="payment_methods[{{ $paymentMethod->pivot->id }}][id]" value="{{ $paymentMethod->id }}" />
                                                        {{ $paymentMethod->name }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <select class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][processor]">
                                                            @foreach (\App\Enums\Processor::toArray() as $processor)
                                                                <option value="{{ $processor }}" @if ($processor == $paymentMethod->pivot->processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    %
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control input-money" name="payment_methods[{{ $paymentMethod->pivot->id }}][tax]" value="{{ moneyDecimal($paymentMethod->pivot->tax) ?? 0 }}" />
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    %
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control input-money" name="payment_methods[{{ $paymentMethod->pivot->id }}][discount]" value="{{ moneyDecimal($paymentMethod->pivot->discount) ?? 0 }}" />
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][limiter]" value="{{ $paymentMethod->pivot->limiter ?? 0 }}" />
                                                    </td>
                                                    <td class="align-middle">
                                                        <select class="form-control form-control-sm" name="payment_methods[{{ $paymentMethod->pivot->id }}][first_installment_billet]">
                                                            <option value="1" @if ($paymentMethod->pivot->first_installment_billet == 1) selected @endif>{{ __('messages.yes') }}</option>
                                                            <option value="0" @if ($paymentMethod->pivot->first_installment_billet == 0) selected @endif>{{ __('messages.no') }}</option>
                                                        </select>
                                                        <select class="form-control form-control-sm" name="payment_methods[{{ $paymentMethod->pivot->id }}][first_installment_billet_method_id]">
                                                            <option value></option>
                                                            @foreach ($billetPaymentMethods as $billetPaymentMethod)
                                                                <option value="{{ $billetPaymentMethod->id }}" @if ($paymentMethod->pivot->first_installment_billet_method_id == $billetPaymentMethod->id) selected @endif>{{ lastWord($billetPaymentMethod->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][max_installments]" value="{{ $paymentMethod->pivot->max_installments ?? 0 }}" />
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('backend.packages.destroyPaymentMethod', [$package, $paymentMethod->pivot->id]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="table-warning font-bold">
                                                <td colspan="8">{{__('resources.packages.payment_table.nac')}}</td>
                                            </tr>
                                            @foreach ($paymentMethods['national'] as $paymentMethod)
                                                <tr>
                                                    <td class="align-middle">
                                                        <input type="hidden" name="payment_methods[{{ $paymentMethod->pivot->id }}][id]" value="{{ $paymentMethod->id }}" />
                                                        {{ $paymentMethod->name }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <select class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][processor]">
                                                            @foreach (\App\Enums\Processor::toArray() as $processor)
                                                                <option value="{{ $processor }}" @if ($processor == $paymentMethod->pivot->processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    %
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control input-money" name="payment_methods[{{ $paymentMethod->pivot->id }}][tax]" value="{{ $paymentMethod->pivot->tax ?? 0 }}" />
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    %
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control input-money" name="payment_methods[{{ $paymentMethod->pivot->id }}][discount]" value="{{ $paymentMethod->pivot->discount ?? 0 }}" />
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][limiter]" value="{{ $paymentMethod->pivot->limiter ?? 0 }}" />
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm" name="payment_methods[{{ $paymentMethod->pivot->id }}][first_installment_billet]">
                                                            <option value="1" @if ($paymentMethod->pivot->first_installment_billet == 1) selected @endif>{{ __('messages.yes') }}</option>
                                                            <option value="0" @if ($paymentMethod->pivot->first_installment_billet == 0) selected @endif>{{ __('messages.no') }}</option>
                                                        </select>
                                                        <select class="form-control form-control-sm" name="payment_methods[{{ $paymentMethod->pivot->id }}][first_installment_billet_method_id]">
                                                            <option value></option>
                                                            @foreach ($billetPaymentMethods as $billetPaymentMethod)
                                                                <option value="{{ $billetPaymentMethod->id }}" @if ($paymentMethod->pivot->first_installment_billet_method_id == $billetPaymentMethod->id) selected @endif>{{ lastWord($billetPaymentMethod->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" class="form-control" name="payment_methods[{{ $paymentMethod->pivot->id }}][max_installments]" value="{{ $paymentMethod->pivot->max_installments ?? 0 }}" />
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('backend.packages.destroyPaymentMethod', [$package, $paymentMethod->pivot->id]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer">
            @if (user()->canUpdatePackage($package))
                <button type="submit" class="btn btn-primary save"
                @if($package->hasBookings())
                titleforpage="{{ __('question.warning.save.title_with_booking_change')}}"                                            
                @endif
                >
                    <i class="fa fa-save"></i> {{ __('messages.save') }}
                </button>
            @endif

            <a href="{{ route('backend.packages.index') }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>

            @if (user()->canDeletePackage())
                <a href="{{ route('backend.packages.destroy', $package) }}"  class="btn btn-danger delete pull-right">
                    <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                </a>
            @endif
        </div>
    </div>
@if (user()->canManagePackageDetails())
</form>
@endif
@endsection

@push('styles')
    <link href="/backend/vendors/summernote/dist/summernote.css" rel="stylesheet" />
    <link rel="stylesheet" href="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"  />
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
@endpush

@push('scripts')
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script src="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script src="/backend/vendors/moment/moment.js"></script>

    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            onChangeDateTime:function(dp ,$input){
                checkStartDateRule(dp, $input);
            },
            onGenerate:function(dp, $input){
                checkStartDateRule(dp, $input);
            },
            mask: true
        });
        function checkStartDateRule(dp, $input){
            var duration = "{{ $event->getDuration() ?? '' }}";
            if(duration == 'one-day' && $input.attr("name") == "starts_at"){
                var value       = $input.val();
                var startAt     = moment(value, "DD/MM/YYYY, hh:mm"); // July 23rd 2021, 3:13:34 pm
                $("input[name=ends_at]").val(startAt.format('DD/MM/YYYY, HH:mm'));
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 250,
            });

            fillAddress({
                country: "{{ old('address.country', $package->address->country) }}",
                state: "{{ old('address.state', $package->address->state) }}",
                city: "{{ old('address.city', $package->address->city) }}"
            }, @php
                if($disabled != "") echo "true"; else echo "false";
               @endphp
            );

            function handleEventDuration(duration) {
                let oneDayInput = $('[data-one-day-only]');
                let rangeDateInput = $('[data-range-date-only]');
                if (duration == 'one-day') {
                    oneDayInput.show();
                    rangeDateInput.val('');
                    rangeDateInput.hide();
                } else {
                    oneDayInput.show();
                    rangeDateInput.show();
                }
            }

            let inputSelectEvent = $('select[name="event_id"]');

            if (inputSelectEvent != undefined) {
                inputSelectEvent.change(function (e) {
                    let eventId = $(this).val() || 0;
                    let eventDuration = $(this).find('option[value='+ eventId +']').data('duration');

                    handleEventDuration(eventDuration);
                });
            }

            handleEventDuration("{{ $event->getDuration() ?? '' }}");
        });
    </script>
    @include('backend.template.scripts.select-events')
@endpush
