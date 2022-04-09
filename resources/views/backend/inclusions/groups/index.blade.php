@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.inclusions.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.inclusions.index', ['type' => $type]) }}">{{ __('resources.inclusions.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.inclusions.groups.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.inclusions.groups.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.inclusions.groups.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.inclusions.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="inclusionsTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('resources.inclusions.groups.model.name') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td class="text-uppercase"><a href="{{ route('backend.inclusions.groups.edit', ['type' => $type, $group]) }}">{{ $group->name }}</a></td>
                                    <td class="text-center skip">
                                        <a href="{{ route('backend.inclusions.groups.destroy', ['type' => $type, $group]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </tr>
                                </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#inclusionGroupsTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
