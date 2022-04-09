<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>{{ __('messages.filters') }}</th>
        </tr>
    </thead>
    <tr>
        <td>
            <form method="GET" action="{{ route('backend.reports.report_bills.index') }}" autocomplete="off">
                <div class="tab-pane active" id="basic-info" role="">
                    <div class="row">
                        <div class="form-group col-md-1">
                            <label class="form-control-label">
                                <strong>{{__('report.loc')}}</strong>
                            </label>
                            <input type="text" class="form-control" style="width: 100%; height:36px;" name="booking_id" value="{{ isset($_filter_params['booking_id'])?$_filter_params['booking_id']:'' }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.pay_form')}}</strong>
                            </label>

                            <select name="payment_method_id" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach ($paymentMethods->sortBy("category") as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}"
                                    @if(isset($_filter_params['payment_method_id']) && $_filter_params['payment_method_id'] == $paymentMethod->id) selected @endif
                                    >{{ $paymentMethod->category }} - {{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.booking_status')}}</strong>
                            </label>
                            <select name="booking_status" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if(isset($_filter_params['booking_status']) && $_filter_params['booking_status'] == \App\Enums\ProcessStatus::CONFIRMED ) selected  @endif>{{__('resources.process-statues.confirmed')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if(isset($_filter_params['booking_status']) && $_filter_params['booking_status'] == \App\Enums\ProcessStatus::CANCELED ) selected  @endif>{{__('resources.process-statues.canceled')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::BLOCKED }}" @if(isset($_filter_params['booking_status']) && $_filter_params['booking_status'] == \App\Enums\ProcessStatus::BLOCKED ) selected  @endif>{{__('resources.process-statues.blocked')}}</option>
                            </select>
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
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.gateway')}}</strong>
                            </label>
                            <select name="processor" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach (explode(",", \App\Enums\Processor::toString()) as $processor)
                                    <option value="{{ $processor }}"
                                    @if(isset($_filter_params['processor']) && $_filter_params['processor'] == $processor) selected @endif
                                    >{{ $processor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.payment_bill_status')}}</strong>
                            </label>
                            <select name="bookingBill_status" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if(isset($_filter_params['bookingBill_status']) && $_filter_params['bookingBill_status'] == \App\Enums\ProcessStatus::PENDING ) selected  @endif>{{__('resources.process-statues.pending')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PAID }}" @if(isset($_filter_params['bookingBill_status']) && $_filter_params['bookingBill_status'] == \App\Enums\ProcessStatus::PAID ) selected  @endif>{{__('resources.process-statues.paid')}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.payment_status')}}</strong>
                            </label>
                            <select name="booking_payment_status" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if(isset($_filter_params['booking_payment_status']) && $_filter_params['booking_payment_status'] == \App\Enums\ProcessStatus::PENDING ) selected  @endif>{{__('resources.process-statues.pending')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PAID }}" @if(isset($_filter_params['booking_payment_status']) && $_filter_params['booking_payment_status'] == \App\Enums\ProcessStatus::PAID ) selected  @endif>{{__('resources.process-statues.paid')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::REFUNDED }}" @if(isset($_filter_params['booking_payment_status']) && $_filter_params['booking_payment_status'] == \App\Enums\ProcessStatus::REFUNDED ) selected  @endif>{{__('resources.process-statues.refunded')}}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if(isset($_filter_params['booking_payment_status']) && $_filter_params['booking_payment_status'] == \App\Enums\ProcessStatus::CANCELED ) selected  @endif>{{__('resources.process-statues.canceled')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-sm btn-warning">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                    </div>
                </div>
            </form>
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
