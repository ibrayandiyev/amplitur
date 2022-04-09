@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-7">
        @if(isset($company))
            <h3 class="text-themecolor">{{ $company->company_name }} - {{ __('resources.offers.name-plural') }}</h3>
        @else
            <h3 class="text-themecolor">{{ __('resources.offers.name-plural') }}</h3>
        @endif
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            @if(isset($provider) && isset($company))
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.edit', [$provider, $company]) }}">{{ $company->company_name }}</a>
            </li>
            @endif
            <li class="breadcrumb-item active">{{ __('resources.offers.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-5">
        <div class="float-right">
            @if(isset($provider) && isset($company))
            <a href="{{ route('backend.providers.companies.offers.prepare', [$provider, $company]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.offers.create') }}
            </a>
            @else
            <a href="{{ route('backend.offers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.offers.create') }}
            </a>
            @endif
        </div>
    </div>
</div>

@include('backend.offers.filters_analytic')

<div class="row">
    <div class="col-12">
        @php
            $packages = $packages->sortBy("event.name");
        @endphp
        @foreach($packages as $package)
        @if($package->offers->count() >0)
            <div class="card">

                <div class="labelx label-service">
                    {{ $package->extendedName}}
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        @include('backend.offers.table_analytic', ['packageId' => $package->id, 'offers' => $package->offers])
                    </div>

                </div>
            </div>
        @endif

        @endforeach;

    </div>
</div>

@endsection
