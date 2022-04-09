@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __("navigation.rel_email") }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('navigation.reports') }}</li>
            <li class="breadcrumb-item active">{{ __("navigation.rel_email") }}</li>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __("navigation.rel_email") }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.reports.report_email.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
