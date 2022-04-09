@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.hotels.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.hotels.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.hotels.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.hotels.create') }}
            </a>
            <button class="btn btn-sm btn-warning toggle-filter">
                <i class="fa fa-filter"></i>
                {{ __('messages.filter') }}
            </button>
        </div>
    </div>
</div>

@include('backend.hotels.filters')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.hotels.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.hotels.table')
                </div>
            </div>

            @if(session()->get("lt_referer") >0)
                @php
                    $longtripRoute = \App\Models\LongtripRoute::find(session()->get("lt_referer"));
                @endphp
                <div class="card-body">
                    <div class="table-responsive">
                    <a href="{{ route('backend.providers.companies.offers.longtrip.editRoute',
                        ['provider' => $longtripRoute->offer->provider, 'company' => $longtripRoute->offer->company, 'offer' => $longtripRoute->offer, 'longtripRoute' => $longtripRoute]) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i>
                        {{ __('resources.btn.return_hotel_offers') }}
                    </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
