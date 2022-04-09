@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.inclusions.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.inclusions.index', ['type' => $type]) }}">{{ __('resources.inclusions.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.inclusions.groups.index', ['type' => $type]) }}">{{ __('resources.inclusions.groups.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $group->name }}</li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.inclusions.groups.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.inclusions.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="inclusionGroupsForm" method="post" action="{{ route('backend.inclusions.groups.update', ['type' => $type, $group]) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put">
                <div class="labelx label-service">
                    {{ __('resources.inclusions.groups.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">{{ __('resources.inclusions.groups.model.name') }}</label>
                            <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control text-uppercase" value="{{ old('name', $group->name) }}" required/>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.inclusions.groups.index', ['type' => $type]) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
