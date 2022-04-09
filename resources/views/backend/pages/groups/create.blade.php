@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.page-groups.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.pages.groups.index') }}">
                    {{ __('resources.page-groups.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.page-groups.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="pageGroupForm" method="post" action="{{ route('backend.pages.groups.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.page-groups.name-plural') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pt-br" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.portuguese') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#en" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.english') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#es" role="tab">
                            <span class="hidden-sm-up"><i class="ti-agenda"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.spanish') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="pt-br" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.name') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="name[pt-br]" value="{{ old('name.pt-br') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="en" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.name') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="name[en]" value="{{ old('name.en') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="es" role="tabpanel">
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.pages.model.name') }}</strong>
                            </label>
                            <input type="text" class="form-control" name="name[es]" value="{{ old('name.es') }}" />
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.pages.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
