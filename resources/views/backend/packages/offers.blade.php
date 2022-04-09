@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.offers.name-plural') }} / {{ $package->extendedName }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.packages.index') }}">
                    {{ __('resources.packages.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.packages.edit', $package) }}">
                    {{ $package->extendedName }}
                </a>
            </li>
            <li class="breadcrumb-item active">
                {{ __('resources.offers.name-plural') }}
            </li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.offers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.offers.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.offers.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.offers.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
