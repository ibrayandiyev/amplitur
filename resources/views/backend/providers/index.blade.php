@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.providers.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.providers.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.providers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.providers.create') }}
            </a>
        </div>
    </div>
</div>

@include('backend.providers.filters')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.providers.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.providers.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#providersTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
