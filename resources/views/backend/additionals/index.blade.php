@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.additionals.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.additionals.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.additionals.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.additionals.create') }}
            </a>
            <a href="{{ route('backend.additionals.groups.index') }}" class="btn btn-secondary">
                <i class="fa fa-list"></i>
                {{ __('resources.additionals.groups.index') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.additionals.name-plural') }}
            </div>
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#list" role="tab">
                        <span class="hidden-sm-up"><i class="ti-user"></i></span>
                        <span class="hidden-xs-down">{{ __('resources.additionals.register_add') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#offers" role="tab">
                        <span class="hidden-sm-up"><i class="ti-user"></i></span>
                        <span class="hidden-xs-down">{{ __('resources.additionals.model.offer_link') }} </span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active p-0" id="list" role="tab-panel">
                    <div class="p-t-20">
                        @include('backend.additionals.table')
                    </div>
                </div>
                <div class="tab-pane p-0" id="offers" role="tab-panel">
                    <div class="p-t-20">
                        @include('backend.additionals.offers')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
