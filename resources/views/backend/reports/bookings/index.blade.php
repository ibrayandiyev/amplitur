@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.bookings.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('navigation.reports') }}</li>
            <li class="breadcrumb-item active">{{ __('resources.bookings.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.reports.bookings.export') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-file-excel-o"></i>
                 {{ __('messages.export') }}
            </a>
            <button class="btn btn-sm btn-warning toggle-filter">
                <i class="fa fa-filter"></i>
                {{ __('messages.filter') }}
            </button>
        </div>
    </div>
</div>

@include('backend.reports.bookings.filters')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.bookings.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.reports.bookings.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
