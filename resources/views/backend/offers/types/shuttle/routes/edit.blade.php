@php
    $canChange = !(user()->isProvider() && $offer->hasBookings());
    $disabled = $canChange ? null : 'disabled';
    $readonly = $canChange ? null : 'readonly';
    $active_basic_info = $active_boarding = $active_inclusions = "";
    switch($navigation){
        default:
        case 'basic-info':
            $active_basic_info  = "active";
            break;
        case 'boarding':
            $active_boarding      = "active";
            break;
        case 'inclusions':
            $active_inclusions = "active";
            break;
    }
@endphp

@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">
            {{ $offer->package->extendedName }} //
            {{ __('resources.shuttle-routes.edit') }}
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
                - {{ $shuttleRoute->name }}</li>
        </ol>
    </div>
</div>

<form id="shuttleRouteForm" method="post" action="{{ route('backend.providers.companies.offers.shuttle.updateRoute', [$provider, $company, $offer, $shuttleRoute]) }}" autocomplete="off">
    <input type="hidden" name="_method" value="put">
    <input type="hidden" name="navigation" value="{{ old('navigation', 'basic-info') }}">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.shuttle.info') }} - {{ __('resources.shuttle-routes.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $active_basic_info }}" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.shuttle-routes.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $active_boarding }}" data-toggle="tab" href="#boarding" role="tab">
                            <span class="hidden-sm-up"><i class="ti-truck"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.shuttle-routes-boarding.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $active_inclusions }}" data-toggle="tab" href="#inclusions" role="tab">
                            <span class="hidden-sm-up"><i class="ti-plus"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.shuttle-routes.model.inclusions') }} & {{ __('resources.shuttle-routes.model.exclusions') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane {{ $active_basic_info }}" id="basic-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $shuttleRoute->name) }}" {{$disabled}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('capacity')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.capacity') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" class="form-control" name="capacity" value="{{ old('capacity', $shuttleRoute->capacity) }}" placeholder="20">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('fields.sale_dates')) has-danger @endif" styles="display:none;">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.fields.sale_dates') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="select2 select2-multiple form-control" name="fields[sale_dates][]" style="width: 100%" multiple {{$disabled}}>
                                    @foreach ($offer->package->period as $period)
                                        <option value="{{ $period->format('Y-m-d') }}" @if (in_array($period->format('Y-m-d'), old('fields.sale_dates', $shuttleRoute->fields['sale_dates'] ?? []))) selected @endif>{{ $period->format('d/m/Y') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('observations')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.observations') }}</strong>
                                </label>
                                <select id="selection1" name="observations[]" class="select2 m-b-10 select2-multiple copyPasteObservation" style="width: 100%" multiple="multiple" {{$disabled}}>
                                    @foreach ($observations as $observation)
                                        <option value="{{ $observation->id }}" @if($shuttleRoute->observations->contains('id', $observation->id)) selected @endif>{{ $observation->name }}</option>
                                    @endforeach
                                </select>
                                @include("backend.tools.copypastebutton", ["targetObject" => "copyPasteObservation"])
                            </div>
                        </div>

                        @if (user()->canManageOfferExtras($offer))
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.shuttle-routes.model.extra_observations') }}</strong>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="basic-info-extra_observations-pt-br" class="tab-pane active">
                                            <textarea class="form-control summernote" name="extra_observations[pt-br]" placeholder="pt-br">{!! $shuttleRoute->getTranslation('extra_observations', 'pt-br', false) !!}</textarea>
                                        </div>
                                        <div id="basic-info-extra_observations-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_observations[en]" placeholder="en">{!! $shuttleRoute->getTranslation('extra_observations', 'en', false) !!}</textarea>
                                        </div>
                                        <div id="basic-info-extra_observations-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_observations[es]" placeholder="es">{!! $shuttleRoute->getTranslation('extra_observations', 'es', false) !!}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30">
                                        <li class=" nav-item"><a href="#basic-info-extra_observations-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#basic-info-extra_observations-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#basic-info-extra_observations-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane {{ $active_inclusions }}" id="inclusions" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('inclusions')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.shuttle-routes.model.inclusions') }}</strong>
                                </label>
                                @php
                                $inclusions = $inclusions->sortBy("name");
                                @endphp
                                <select id='inclusions1' name="inclusions[]" class="select2 m-b-10 select2-multiple copyPasteInclusion" style="width: 100%" multiple="multiple" {{$disabled}}>
                                    @foreach ($inclusions as $inclusion)
                                        @php 
                                            if($inclusion->allowed_companies != null){
                                                if(user()->companies && !in_array_any(user()->companies->pluck('id')->toArray(), $inclusion->allowed_companies)){
                                                    continue;
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $inclusion->id }}" @if($shuttleRoute->inclusions->contains('id', $inclusion->id)) selected @endif>{{ $inclusion->name }}</option>
                                    @endforeach
                                </select>
                                @include("backend.tools.copypastebutton", ["targetObject" => "copyPasteInclusion"])
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
                                            <textarea class="form-control summernote" name="extra_inclusions[pt-br]" placeholder="pt-br">{!! $shuttleRoute->getTranslation('extra_inclusions', 'pt-br', false) !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-inclusions-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_inclusions[en]" placeholder="en">{!! $shuttleRoute->getTranslation('extra_inclusions', 'en', false) !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-inclusions-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_inclusions[es]" placeholder="es">{!! $shuttleRoute->getTranslation('extra_inclusions', 'es', false) !!}</textarea>
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
                                <select id='exclusions' name="exclusions[]" class="select2 m-b-10 select2-multiple copyPasteExclusion" style="width: 100%" multiple="multiple" {{$disabled}}>
                                    @foreach ($exclusions as $exclusion)
                                        <option value="{{ $exclusion->id }}" @if($shuttleRoute->exclusions->contains('id', $exclusion->id)) selected @endif>{{ $exclusion->name }}</option>
                                    @endforeach
                                </select>
                                @include("backend.tools.copypastebutton", ["targetObject" => "copyPasteExclusion"])
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
                                            <textarea class="form-control summernote" name="extra_exclusions[pt-br]" placeholder="pt-br">{!! $shuttleRoute->getTranslation('extra_exclusions', 'pt-br', false) !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-exclusions-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_exclusions[en]" placeholder="en">{!! $shuttleRoute->getTranslation('extra_exclusions', 'en', false) !!}</textarea>
                                        </div>
                                        <div id="inclusions-extra-exclusions-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="extra_exclusions[es]" placeholder="es">{!! $shuttleRoute->getTranslation('extra_exclusions', 'es', false) !!}</textarea>
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
                    <div class="tab-pane {{ $active_boarding }}" id="boarding" role="tab-panel">
                        @include('backend.offers.types.shuttle.routes.boarding.table')
                        <a href="{{ route('backend.providers.companies.offers.shuttle.createBoardingLocation', [$provider, $company, $offer, $shuttleRoute]) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i>
                            {{ __('resources.shuttle-routes-boarding.create') }}
                        </a>
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
            @if ($canChange)
            <a href="{{ route('backend.providers.companies.offers.shuttle.destroyRoute', [$provider, $company, $offer, $shuttleRoute]) }}"  class="btn btn-danger delete pull-right">
                <i class="fa fa-trash"></i> {{ __('messages.delete') }}
            </a>
            @endif
        </div>
    </div>
</form>
@endsection

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
@endpush

@push('scripts')
    <script type="text/javascript">
        $('.selectpicker').selectpicker();
    </script>
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
<script type="text/javascript">
    $(function (){
        $('.summernote').summernote({
            height: 150,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    });
    </script>
@endpush
