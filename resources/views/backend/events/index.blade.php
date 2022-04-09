@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.events.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.events.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.events.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.events.create') }}
            </a>
            <button class="btn btn-sm btn-warning toggle-filter">
                <i class="fa fa-filter"></i>
                {{ __('messages.filter') }}
            </button>
            {{-- @if (!App\Models\Flag::isImportingEvents()) --}}
            <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#importEventModal">
                <i class="fa fa-upload"></i>
                 {{ __('messages.import') }}
            </button>
            <a href="{{ route('backend.events.export') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-file-excel-o"></i>
                 {{ __('messages.export') }}
            </a>
            {{-- @endif --}}
        </div>
    </div>
</div>

@include('backend.events.filters')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.events.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.events.table')
                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.events.import')

@endsection
