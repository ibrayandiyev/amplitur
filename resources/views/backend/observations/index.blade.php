@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.observations.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.observations.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.observations.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.observations.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.observations.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="observationsTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('resources.portuguese') }}</th>
                                <th>{{ __('resources.english') }}</th>
                                <th>{{ __('resources.spanish') }}</th>
                                <th>{{ __('resources.observations.model.is_exclusive') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($observations as $observation)
                                <tr>
                                    <td class=""><a href="{{ route('backend.observations.edit', ['type' => $type, $observation]) }}">{{ $observation->getTranslation('name', 'pt-br', false) }}</a></td>
                                    <td class="">{{ $observation->getTranslation('name', 'en', false) }}</td>
                                    <td class="">{{ $observation->getTranslation('name', 'es', false) }}</td>
                                    <td class="">{{ $observation->isExclusiveLabel }}</td>
                                    <td class="text-center skip">
                                        <a href="{{ route('backend.observations.destroy', ['type' => $type, $observation]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            $('#observationsTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
