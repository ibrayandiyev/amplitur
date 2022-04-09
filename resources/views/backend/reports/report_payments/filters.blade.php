


    <table id="filter" class="table color-bordered-table warning-bordered-table">
        <thead>
            <tr>
                <th>{{ __('messages.filters') }}</th>
            </tr>
        </thead>
            <tr>
                <td>
            <div class="tab-pane active" id="basic-info" role="">
                <form id="packageForm" method="GET" action="{{ route('backend.reports.report_payments.index') }}" autocomplete="off">
                    <div class="form-group col-md-1">
                        <label class="form-control-label">
                            <strong>{{__('report.loc')}}</strong>
                        </label>
                        <input type="text" class="form-control" style="width: 100%; height:36px;" name="booking_id" value="{{ isset($_filter_params['booking_id'])?$_filter_params['booking_id']:'' }}">
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
