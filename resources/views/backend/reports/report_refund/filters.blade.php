<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                    <form id="packageForm" method="GET" action="{{ route('backend.reports.report_refund.index') }}" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-md-1">
                                <label class="form-control-label">
                                    <strong>{{__('report.loc')}}</strong>
                                </label>
                                <input type="text" class="form-control" style="width: 100%; height:36px;" name="booking_id" value="{{ isset($_filter_params['booking_id'])?$_filter_params['booking_id']:'' }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{__('report.start_date')}}</strong>
                                </label>
                                <input type="text" maxlength="10" class="form-control datepicker" style="width: 100%; height:36px;" name="start_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-inputmask-placeholder="dd/mm/aaaa" placeholder="__/__/____"  value="{{ isset($_filter_params['start_date'])?$_filter_params['start_date']:date('d/m/Y') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{__('report.end_date')}}</strong>
                                </label>
                                <input type="text" maxlength="10" class="form-control datepicker" style="width: 100%; height:36px;" name="end_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-inputmask-placeholder="dd/mm/aaaa" placeholder="__/__/____"  value="{{ isset($_filter_params['end_date'])?$_filter_params['end_date']:date('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="float-right">
                            <button class="btn btn-sm btn-warning toggle-filter">
                                <i class="fa fa-filter"></i>
                                {{ __('messages.filter') }}
                            </button>
                        </div>

                    </form>
            </div>
        </td>
    </tr>
</table>
@push('styles')
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
        $('.datepicker').inputmask();

    </script>
@endpush
