@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.prebookings.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.prebookings.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.prebookings.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.prebookings.create') }}
            </a>
            <button class="btn btn-sm btn-warning toggle-filter">
                <i class="fa fa-filter"></i>
                {{ __('messages.filter') }}
            </button>
        </div>
    </div>
</div>

@include('backend.prebookings.filters')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.prebookings.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.prebookings.table')
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
            $('#prebookingsTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
