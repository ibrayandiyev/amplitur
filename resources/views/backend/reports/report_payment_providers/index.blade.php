@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __("navigation.payment_future") }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('navigation.reports') }}</li>
            <li class="breadcrumb-item active">{{ __("navigation.payment_future") }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __("navigation.payment_future") }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.reports.report_payment_providers.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
