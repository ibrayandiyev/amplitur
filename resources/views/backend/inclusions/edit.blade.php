@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.inclusions.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.inclusions.index', ['type' => $type]) }}">{{ __('resources.inclusions.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $inclusion->name }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.inclusions.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.inclusions.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="clientForm" method="post" action="{{ route('backend.inclusions.update', ['type' => $type, $inclusion]) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put">
                <div class="labelx label-service">
                    {{ __('resources.inclusions.info') }}
                </div>

                <div class="card-body row">
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_portuguese') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[pt-br]" class="form-control " value="{{ old('name', $inclusion->getTranslation('name', 'pt-br', false)) }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_english') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[en]" class="form-control " value="{{ old('name', $inclusion->getTranslation('name', 'en', false)) }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_spanish') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[es]" class="form-control " value="{{ old('name', $inclusion->getTranslation('name', 'es', false)) }}" required/>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-control-label">{{ __('resources.inclusions.model.is_exclusive') }}</label>
                        <span class="text-danger">*</span>
                        <select class="form-control " name="is_exclusive">
                            <option value="0" @if (old('is_exclusive', $inclusion->is_exclusive) == 0) selected @endif>{{ __('messages.no') }}</option>
                            <option value="1" @if (old('is_exclusive', $inclusion->is_exclusive) == 1) selected @endif>{{ __('messages.yes') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-9 @if($errors->has('allowed_companies')) has-danger @endif allowed_companies_row" styles="display:none;">
                        <label class="form-control-label">
                            <strong>{{ __('resources.label.info_by_company') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="select2 select2-multiple form-control" name="allowed_companies[]" style="width: 100%" multiple>
                            <option value=""></option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @if (in_array($company->id, old('allowed_companies', $inclusion->allowed_companies ?? []))) selected @endif>{{ $company->provider->name }} - {{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.inclusions.index', ['type' => $type]) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
