@extends('backend.template.default')
@section('content')

@php
    if(!isset($navigation) || $navigation == null){
        $navigation = "sales-info";
    }
    $activeSi = $activeItem = $activePassengers = $activePayment = $activeVouchers =
    $activeLogs = "";
    switch($navigation){
        default:
        case "sales-info":
            $activeSi = "active";
        break;
        case "pricing-tab":
            $activeItem = "active";
        break;
    }
@endphp
<div class="row page-titles">
    <div class="col-md-10">
        <h3 class="text-themecolor">
            {{ $offer->package->extendedName }}
        </h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }} : </a>
                <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }} : </a>
                <a href="{{ route('backend.providers.companies.edit', [$provider, $company]) }}">{{ $company->company_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}">{{ __('resources.offers.name-plural') }} : </a>
                {{ $offer->package->name }}
            </li>
        </ol>
    </div>
    <div class="col-md-2">
        <div class="float-right">
            <a href="{{ route('backend.providers.companies.offers.prepare', [$provider, $company]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.offers.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @include('backend.offers.types.' . $offer->type . '.edit')
    </div>
</div>
@endsection
