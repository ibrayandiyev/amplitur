@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.pages.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.pages.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.pages.groups.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-list"></i>
                 {{ __('resources.page-groups.name-plural') }}
            </a>
            <a href="{{ route('backend.pages.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.pages.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.pages.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @include('backend.pages.table')
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
            $('#pagesTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
