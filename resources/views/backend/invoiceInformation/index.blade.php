@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.invoiceInformation.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.invoiceInformation.name-plural') }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.invoiceInformation.create', []) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.invoiceInformation.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.invoiceInformation.name-plural') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="invoiceInformationTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('resources.invoiceInformation.model.currency') }} </th>
                                <th>{{ __('resources.invoiceInformation.model.name') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoiceInformation as $invoice)
                                <tr>
                                    <td class="text-uppercase"><a href="{{ route('backend.invoiceInformation.edit', [$invoice]) }}">{{ $invoice->currency->name }}</a></td>
                                    <td class="text-uppercase">{!! $invoice->description !!}</td>
                                    <td class="text-center skip">
                                        <a href="{{ route('backend.invoiceInformation.destroy', [$invoice]) }}" token="{{ csrf_token() }}"  class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
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
            $('#invoiceInformationTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
