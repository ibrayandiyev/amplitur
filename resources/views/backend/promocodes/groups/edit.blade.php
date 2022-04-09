@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-7">
        <h3 class="text-themecolor">{{ $promocodeGroup->name }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.promocodes.index') }}">{{ __('resources.promocodes.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">{{ __('resources.promocode-groups.name-plural') }}</li>
            <li class="breadcrumb-item active">{{ $promocodeGroup->name }}</li>
        </ol>
    </div>
    <div class="col-md-5">
        <div class="float-right">
            <a href="{{ route('backend.promocodes.create', $promocodeGroup) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.promocodes.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="promocodeGroupForm" method="post" action="{{ route('backend.promocodes.groups.update', $promocodeGroup) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put"/>

                <div class="labelx label-service">
                    {{ __('resources.promocode-groups.info') }}
                </div>

                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#promocodes" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.promocodes.name-plural') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocode-groups.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="promocode-group-name-pt-br" class="tab-pane active">
                                        <input type="text" class="form-control text-uppercase" name="name[pt-br]" value="{{ old('name.pt-br', $promocodeGroup->getTranslation('name', 'pt-br')) }}" required>
                                    </div>
                                    <div id="promocode-group-name-en" class="tab-pane">
                                        <input type="text" class="form-control text-uppercase" name="name[en]" value="{{ old('name.en', $promocodeGroup->getTranslation('name', 'en')) }}" required>
                                    </div>
                                    <div id="promocode-group-name-es" class="tab-pane">
                                        <input type="text" class="form-control text-uppercase" name="name[es]" value="{{ old('name.es', $promocodeGroup->getTranslation('name', 'es')) }}" required>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-t-10">
                                    <li class="nav-item"><a href="#promocode-group-name-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#promocode-group-name-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#promocode-group-name-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.packages.name') }}</strong>
                                </label>
                                <select class="form-control text-uppercase select2" name="package_id" style="width: 100%;">
                                    <option value>&nbsp;</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" @if (old('package_id', $promocodeGroup->package_id) == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                                <small>{{ __('resources.promocode-groups.hints.packages_let_blank') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active" id="promocodes" role="tabpanel">
                        @include('backend.promocodes.table')
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.promocodes.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
