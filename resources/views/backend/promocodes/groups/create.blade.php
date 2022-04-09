@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.promocode-groups.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.promocodes.index') }}">{{ __('resources.promocodes.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">{{ __('resources.promocode-groups.name-plural') }}</li>
            <li class="breadcrumb-item active">{{ __('resources.promocode-groups.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.promocodes.groups.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.promocode-groups.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="promocodeGroupForm" method="post" action="{{ route('backend.promocodes.groups.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.promocode-groups.info') }}
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
                                    <strong>{{ __('resources.promocode-groups.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="promocode-group-name-pt-br" class="tab-pane active">
                                        <input type="text" class="form-control text-uppercase" name="name[pt-br]" value="{{ old('name.pt-br') }}" required>
                                    </div>
                                    <div id="promocode-group-name-en" class="tab-pane">
                                        <input type="text" class="form-control text-uppercase" name="name[en]" value="{{ old('name.en') }}" required>
                                    </div>
                                    <div id="promocode-group-name-es" class="tab-pane">
                                        <input type="text" class="form-control text-uppercase" name="name[es]" value="{{ old('name.es') }}" required>
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
                                <select class="form-control text-uppercase select2" name="package_id">
                                    <option value>&nbsp;</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" @if (old('package_id') == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                                <small>{{ __('resources.promocode-groups.hints.packages_let_blank') }}</small>
                            </div>
                        </div>
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
