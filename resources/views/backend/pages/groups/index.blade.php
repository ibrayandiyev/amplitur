@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.page-groups.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.pages.index') }}">
                    {{ __('resources.pages.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.page-groups.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.pages.groups.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.page-groups.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.page-groups.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.pages.groups.table')
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
            $('#pageGroupsTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
