@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.packages.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.packages.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.packages.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.packages.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.packages.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.packages.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
