@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.hotel-accommodations-types.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.configs.providers.hotel.accommodation-types.index') }}">{{ __('resources.hotel-accommodation-types.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $hotelAccommodationType->name }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.configs.providers.hotel.accommodation-types.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.hotel-accommodation-types.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="hotelAccommodationTypeForm" method="post" action="{{ route('backend.configs.providers.hotel.accommodation-types.update', $hotelAccommodationType) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.hotel-accommodation-types.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.hotel-accommodation-types.model.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="tab-content br-n pn">
                                <div id="hotel-accommodation-type-name-pt-br" class="tab-pane active">
                                    <input type="text" class="form-control" name="name[pt-br]" value="{{ old('name.pt-br', $hotelAccommodationType->getTranslation('name', 'pt-br')) }}">
                                </div>
                                <div id="hotel-accommodation-type-name-en" class="tab-pane">
                                    <input type="text" class="form-control" name="name[en]" value="{{ old('name.en', $hotelAccommodationType->getTranslation('name', 'en')) }}" required>
                                </div>
                                <div id="hotel-accommodation-type-name-es" class="tab-pane">
                                    <input type="text" class="form-control" name="name[es]" value="{{ old('name.es', $hotelAccommodationType->getTranslation('name', 'es')) }}" required>
                                </div>
                            </div>
                            <ul class="nav nav-pills m-t-10">
                                <li class="nav-item"><a href="#hotel-accommodation-type-name-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                <li class="nav-item"><a href="#hotel-accommodation-type-name-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                <li class="nav-item"><a href="#hotel-accommodation-type-name-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3 @if($errors->has('capacity')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.hotel-accommodation-types.model.capacity') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="capacity" value="{{ old('capacity', $hotelAccommodationType->capacity) }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.configs.providers.hotel.accommodation-types.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
