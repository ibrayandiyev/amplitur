@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.hotel-structure.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.configs.providers.hotel.hotel-structure.index') }}">{{ __('resources.hotel-structure.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.hotel-structure.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.configs.providers.hotel.hotel-structure.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.hotel-structure.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="hotelStructureForm" method="post" action="{{ route('backend.configs.providers.hotel.hotel-structure.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.hotel-structure.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.hotel-structure.model.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="tab-content br-n pn">
                                <div id="hotel-structure-name-pt-br" class="tab-pane active">
                                    <input type="text" class="form-control" name="name[pt-br]" value="{{ old('name.pt-br') }}">
                                </div>
                                <div id="hotel-structure-name-en" class="tab-pane">
                                    <input type="text" class="form-control" name="name[en]" value="{{ old('name.en') }}" required>
                                </div>
                                <div id="hotel-structure-name-es" class="tab-pane">
                                    <input type="text" class="form-control" name="name[es]" value="{{ old('name.es') }}" required>
                                </div>
                            </div>
                            <ul class="nav nav-pills m-t-10">
                                <li class="nav-item"><a href="#hotel-structure-name-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                <li class="nav-item"><a href="#hotel-structure-name-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                <li class="nav-item"><a href="#hotel-structure-name-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.configs.providers.hotel.hotel-structure.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection