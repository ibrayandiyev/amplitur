@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.saleCoefficients.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.saleCoefficients.index') }}">{{ __('resources.saleCoefficients.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.saleCoefficients.edit') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.saleCoefficients.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.saleCoefficients.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="saleCoefficientForm" method="post" action="{{ route('backend.saleCoefficients.update', $saleCoefficient) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.saleCoefficients.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.saleCoefficients.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="name[pt-br]" value="{{ old('name', $saleCoefficient->name) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('value')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.saleCoefficients.model.value') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.00001" class="form-control" name="value" value="{{ old('value', $saleCoefficient->value) }}" placeholder="1,00000">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.saleCoefficients.model.is_default') }}</strong>
                                </label>
                                <select class="form-control text-uppercase" name="is_default">
                                    <option value="0" @if (old('is_default', $saleCoefficient->is_default) == 0) selected @endif>{{ __('messages.no') }}</option>
                                    <option value="1" @if (old('is_default', $saleCoefficient->is_default) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.saleCoefficients.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
