@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.longtrip-routes.create') }}</h3>
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
            <li class="breadcrumb-item active">{{ __('resources.longtrip-routes.create') }}</li>
        </ol>
    </div>
</div>

<form id="longtripRouteForm" method="post" action="{{ route('backend.providers.companies.offers.longtrip.storeRoute', [$provider, $company, $offer]) }}" autocomplete="off">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.longtrip-routes.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.longtrip-routes.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-6 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.route_name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-md-6 @if($errors->has('label_name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.route_name_front') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="label_name-pt-br" class="tab-pane active">
                                        <input type="text" name="label_name[pt-br]" class="form-control" value="{{ old('label_name.pt-br') }}" required/>
                                    </div>
                                    <div id="label_name-en" class="tab-pane">
                                        <input type="text" name="label_name[en]" class="form-control" value="{{ old('label_name.en') }}" required/>
                                    </div>
                                    <div id="label_name-es" class="tab-pane">
                                        <input type="text" name="label_name[es]" class="form-control" value="{{ old('label_name.es') }}" required/>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-t-10">
                                    <li class="nav-item"><a href="#label_name-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#label_name-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#label_name-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
                            </div>

                            <div class="form-group col-md-3 @if($errors->has('capacity')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-routes.model.capacity') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" class="form-control" name="capacity" value="{{ old('capacity', 20) }}" placeholder="20">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('fields.sale_dates')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-routes.model.fields.sale_dates') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="select2 select2-multiple form-control" name="fields[sale_dates][]" style="width: 100%" multiple>
                                    @foreach ($offer->package->period as $period)
                                        <option value="{{ $period->format('Y-m-d') }}" @if (in_array($period->format('Y-m-d'), old('fields.sale_dates', []))) selected @endif>{{ $period->format('d/m/Y') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-routes.model.extra_observations') }}</strong>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="basic-info-extra_observations-pt-br" class="tab-pane active">
                                        <textarea class="form-control summernote" name="extra_observations[pt-br]" placeholder="pt-br">{!! old('extra_observations.pt-br') !!}</textarea>
                                    </div>
                                    <div id="basic-info-extra_observations-en" class="tab-pane">
                                        <textarea class="form-control summernote" name="extra_observations[en]" placeholder="en">{!! old('extra_observations.en') !!}</textarea>
                                    </div>
                                    <div id="basic-info-extra_observations-es" class="tab-pane">
                                        <textarea class="form-control summernote" name="extra_observations[es]" placeholder="es">{!! old('extra_observations.es') !!}</textarea>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-b-30">
                                    <li class=" nav-item"><a href="#basic-info-extra_observations-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#basic-info-extra_observations-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#basic-info-extra_observations-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
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

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
@endpush

@push('scripts')
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $('.selectpicker').selectpicker();

        $('.summernote').summernote({
            height: 150,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    </script>
@endpush
