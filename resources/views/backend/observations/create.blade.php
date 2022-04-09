@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.observations.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.observations.index', ['type' => $type]) }}">{{ __('resources.observations.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.observations.create') }}</li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.observations.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.observations.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="observationsForm" method="post" action="{{ route('backend.observations.store', ['type' => $type]) }}" autocomplete="off">
                @csrf
                <input name="type" type="hidden" value="{{ $type }}" />
                <div class="labelx label-service">
                    {{ __('resources.observations.info') }}
                </div>

                <div class="card-body row">
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_portuguese') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[pt-br]" class="form-control " value="{{ old('name') }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_english') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[en]" class="form-control " value="{{ old('name') }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.text_spanish') }}</label>
                        <span class="text-danger">*</span>
                        <input type="text" name="name[es]" class="form-control " value="{{ old('name') }}" required/>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-control-label">{{ __('resources.observations.model.is_exclusive') }}</label>
                        <span class="text-danger">*</span>
                        <select class="form-control " name="is_exclusive">
                            <option value="0" @if (old('is_exclusive') == 0) selected @endif>{{ __('messages.no') }}</option>
                            <option value="1" @if (old('is_exclusive') == 1) selected @endif>{{ __('messages.yes') }}</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.observations.index', ['type' => $type]) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
