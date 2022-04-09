@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.currencies.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.currencies.index') }}">{{ __('resources.currencies.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $currencyQuotation->name }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.currencies.info') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="input-group col-md-12 col-lg-4">
                        <input type="text" name="created_at" maxlength="10" class="form-control datepicker" placeholder="mm/dd/yyyy" value="{{ $date }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <table id="currenciesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%">{{ __('resources.currency-quotation-history.model.created_at') }}</th>
                            <th class="text-center" width="10%">{{ __('resources.currency-quotation-history.model.quotation') }}</th>
                            <th class="text-center" width="20%">{{ __('resources.currency-quotation-history.model.spread') }}</th>
                            <th class="text-center" width="10%">{{ __('resources.currency-quotation-history.model.spreaded_quotation') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencyQuotationHistory as $currenciesQuotation)
                            <tr>
                                <td class="text-center align-middle">{{ $currenciesQuotation->createdAtLabel }}</td>
                                <td class="text-center align-middle">{{ decimal($currenciesQuotation->quotation) }}</td>
                                <td class="text-center align-middle">{{ decimal($currenciesQuotation->spread) }}</td>
                                <td class="text-center">{!! $currenciesQuotation->spreadedQuotation !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <a href="{{ route('backend.currencies.index') }}" class="btn btn-secondary">
                    <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#currenciesTable').DataTable({
                searching: false,
                order: [[ 0, "desc" ]],
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                assumeNearbyYear: true,
                maxViewMode: 2,
            }).on('changeDate', function (e) {
                let url = '{{ route('backend.currencies.edit', $currencyQuotation) }}';
                let date = $('input[name="created_at"]').val().replaceAll('/', '-');

                window.location.replace(`${url}/?created_at=${date}`);
            });
        });
    </script>
@endpush
