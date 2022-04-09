<div class="row" id="pageFilters">
    <div class="col-md-12" style="display: none;">
        <div class="card card-outline-inverse">
            <form method="GET" action="{{ route('backend.financial.bills') }}" autocomplete="off">
                <div class="labelx label-service">
                    <p class="mb-0 text-white">
                        <strong>{{ __('messages.filters') }}</strong>
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label>{{ __('resources.bookings.name') }}</label>
                            <input type="number" class="form-control" name="booking_id" value="{{ $params['booking_id'] ?? '' }}" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label>{{ __('resources.booking-bills.model.status') }}</label>
                            <select name="booking_status" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('booking_status', $params['booking_status'] ?? null) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if (old('booking_status', $params['booking_status'] ?? null) == \App\Enums\ProcessStatus::CONFIRMED) selected @endif>{{ __('resources.process-statues.confirmed') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if (old('booking_status', $params['booking_status'] ?? null) == \App\Enums\ProcessStatus::CANCELED) selected @endif>{{ __('resources.process-statues.canceled') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::SUSPENDED }}" @if (old('booking_status', $params['booking_status'] ?? null) == \App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.suspended') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.payment-methods.name') }}</label>
                            <select name="payment_method_id" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <optgroup label="{{ __('messages.national') }}">
                                    @foreach ($paymentMethods['national'] as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" @if (old('payment_method_id') == $paymentMethod->id) selected @endif>[N] {{ mb_strtoupper($paymentMethod->name) }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{ __('messages.international') }}">
                                    @foreach ($paymentMethods['international'] as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}" @if (old('payment_method_id') == $paymentMethod->id) selected @endif>[I] {{ mb_strtoupper($paymentMethod->name) }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>{{ __('resources.processors.name') }}</label>
                            <select name="processor" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                @foreach (\App\Enums\Processor::toArray() as $processor)
                                    <option value="{{ $processor }}" @if (old('processor', $params['processor'] ?? null) == $processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>{{ __('resources.booking-bills.model.status') }}</label>
                            <select name="bookingBill_status" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PAID }}" @if (old('bookingBill_status', $params['bookingBill_status'] ?? null) == \App\Enums\ProcessStatus::PAID) selected @endif>{{ __('resources.process-statues.paid') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('bookingBill_status', $params['bookingBill_status'] ?? null) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('resources.booking-bills.model.expires_at') }}</label>
                            <div class="input-group">
                                <input type="text" maxlength="10" class="form-control datepicker" name="expires_at[]" value="{{ $params['expires_at'][0] ?? '' }}" placeholder="__/__/____" />
                                <span class="input-group-text">{{ __('messages.to') }}</span>
                                <input type="text" maxlength="10" class="form-control datepicker" name="expires_at[]" value="{{ $params['expires_at'][1] ?? '' }}" placeholder="__/__/____" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                    @if (isset($params))
                        <a href="{{ route('backend.financial.bills') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($params) && count($bookingBills) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning') }}</h4>
                {{ __('messages.no-results') }}
        </div>
    </div>
    @endif
</div>

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
    </script>
@endpush
