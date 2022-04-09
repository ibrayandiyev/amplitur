@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.additionals.groups.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.additionals.index') }}">{{ __('resources.additionals.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">
               {{ __('resources.additionals.groups.name-plural') }}
            </li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.additionals.groups.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.additionals.groups.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.additionals.groups.name-plural') }}
            </div>
            <div class="card-body">
                <table id="additionalsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('resources.additionals.groups.model.name') }}</th>
                            <th>{{ __('resources.additionals.groups.model.selection_type') }}</th>
                            <th class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                            <tr>
                                <td class=""><a href="{{ route('backend.additionals.groups.edit', $group) }}">{{ $group->name }}</a></td>
                                <td class="">{{ $group->selection_type }}</td>
                                <td class="text-center skip">
                                    <a href="{{ route('backend.additionals.groups.destroy', $group) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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

@endsection

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#additionalGroupsTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
