@if (user()->canManageBookingDetails())
@php
    $sumBills = $sumTaxBills = $sumProducts = 0;
    $passengers  = $booking->bookingPassengers->count();
    foreach ($booking->bookingProducts as $key => $bookingProduct) {
        $sumProducts += $bookingProduct->price *$passengers ;
    }
    foreach ($booking->bookingPassengerAdditionals as $key => $bookingAdditional) {
        $sumProducts += $bookingAdditional->price  ;
    }
@endphp
    <div class="tab-pane {{ $activePayment }}" id="payment" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-rounded">
                    <div class="card-header text-dark align-middle">
                        <strong>{{ __('resources.booking-bills.name-plural') }}</strong>
                        <a href="{{ route('backend.bookings.createBill', $booking) }}" class="btn btn-primary btn-xs pull-right">
                            <i class="fa fa-plus"></i>
                            {{ __('messages.add-item') }}
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover table-striped m-0">
                            <thead>
                                <th class="text-center">{{ __('resources.booking-bills.model.installment') }}</th>
                                <th class="text-center">{{ __('resources.booking-bills.model.ct') }}</th>
                                <th>{{ __('resources.payment-methods.name') }}</th>
                                <th class="text-center">{{ __('resources.booking-bills.model.total') }}</th>
                                <th class="text-center">{{ __('resources.processors.name') }}</th>
                                <th class="text-center">{{ __('resources.booking-bills.model.expires_at') }}</th>
                                <th class="text-center">{{ __('resources.booking-bills.model.status') }}</th>
                                <th width="12%" class="text-center"></th>
                            </thead>
                            <tbody>
                                @foreach ($booking->bookingBills->sortBy("installment") as $bookingBill)
                                    <tr>
                                        <td width="2%" class="text-center align-middle">{{ $bookingBill->installment }}</td>
                                        <td width="5%" class="text-center align-middle">
                                            <input type="number" class="form-control text-center p-0 p-l-2" name="bookingBills[{{$bookingBill->id}}][ct]" value="{{ $bookingBill->ct }}"  @if ($bookingBill->isCanceled()) readonly @endif />
                                        </td>
                                        <td width="29%">
                                            <select class="form-control" name="bookingBills[{{$bookingBill->id}}][payment_method_id]" style="width: 100%;" @if ($bookingBill->isCanceled()) readonly @endif>
                                                <optgroup label="{{ __('messages.national') }}">
                                                    @foreach ($paymentMethods['national'] as $paymentMethod)
                                                        <option value="{{ $paymentMethod->id }}" @if (old('bookingBills.'. $bookingBill->id .'.payment_method_id', $bookingBill->payment_method_id) == $paymentMethod->id) selected @endif>[N] {{ mb_strtoupper($paymentMethod->name) }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="{{ __('messages.international') }}">
                                                    @foreach ($paymentMethods['international'] as $paymentMethod)
                                                        <option value="{{ $paymentMethod->id }}" @if (old('bookingBills.'. $bookingBill->id .'.payment_method_id', $bookingBill->payment_method_id) == $paymentMethod->id) selected @endif>[I] {{ mb_strtoupper($paymentMethod->name) }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </td>
                                        <td width="13%" class="align-middle">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        {{ $bookingBill->currency->code }}
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control input-money" name="bookingBills[{{$bookingBill->id}}][total]" value="{{ old('bookingBills.'. $bookingBill->id .'.total', moneyDecimal($bookingBill->total)) }}" inputmode="numeric" style="text-align: right;"  @if ($bookingBill->isCanceled()) readonly @endif>
                                            </div>
                                        </td>

                                        <td width="8%" class="text-center align-middle">
                                            <select class="form-control custom-select-sm p-0 pl-2" name="bookingBills[{{$bookingBill->id}}][processor]" style="width: 100%;"  @if ($bookingBill->isCanceled()) readonly @endif>
                                                @foreach (\App\Enums\Processor::toArray() as $processor)
                                                    <option value="{{ $processor }}" @if ($bookingBill->processor == $processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="9%" class="text-center align-middle">
                                            <input type="text" maxlength="10" class="form-control datepicker" name="bookingBills[{{$bookingBill->id}}][expires_at]" value="{{ $bookingBill->expiresAtLabel }}"  @if ($bookingBill->isCanceled()) readonly @endif placeholder="__/__/____" />
                                        </td>
                                        <td width="10%" class="text-center align-middle">
                                            <select class="form-control custom-select-sm p-0 pl-2" name="bookingBills[{{$bookingBill->id}}][status]" style="width: 100%;"  @if ($booking->isCanceled()) readonly @endif>
                                                @foreach (\App\Enums\ProcessStatus::toArrayPaymentStatus() as $status)
                                                    <option value="{{ $status }}" @if ($bookingBill->status == $status) selected @endif>{{ mb_strtoupper( __("resources.process-statues.".$status)) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td width="5%" class="text-center align-middle">
                                            @if (!$booking->isCanceled())
                                                @if ($bookingBill->canBeDeleted())
                                                    <a href="{{ route('backend.bookings.destroyBill', [$booking, $bookingBill]) }}" class="btn btn-danger delete btn-xs ml-1" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-delete') }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $sumBills       += $bookingBill->total;
                                        $sumTaxBills    += $bookingBill->tax
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="12">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.promocodes.name') }} - AMP TRAVEL</strong>
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="input-group col-md-12">
                                                    <input type="text" maxlength="70" size="700" class="form-control" name="" inputmode="numeric" style="text-align: left;" value="@if($booking->promocode != null) {{$booking->promocode->name}} @endif"  @if ($booking->isCanceled()) readonly @endif/>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            {{ $booking->currency->code }}</span>
                                                        </span>
                                                    </div>

                                                    <input type="text" class="form-control input-money" name="booking[discount_promocode]" inputmode="numeric" style="text-align: right;" value="{{ old('booking.discount_promocode', moneyDecimal($booking->discount_promocode)) }}"  @if ($booking->isCanceled()) readonly @endif/>
                                                </div>
                                                <br>
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.promocodes.name') }} - PROVIDER</strong>
                                                    <span class="text-danger">*</span>
                                                </label>


                                                <div class="input-group col-md-12">
                                                    <input type="text" maxlength="70" size="700" class="form-control" name="" inputmode="numeric" style="text-align: left;" value="@if($booking->promocodeProvider != null) {{$booking->promocodeProvider->name}} @endif"  @if ($booking->isCanceled()) readonly @endif/>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            {{ $booking->currency->code }}</span>
                                                        </span>
                                                    </div>

                                                    <input type="text" class="form-control input-money" name="booking[discount_promocode_provider]" inputmode="numeric" style="text-align: right;" value="{{ old('booking.discount_promocode_provider', moneyDecimal($booking->discount_promocode_provider)) }}"  @if ($booking->isCanceled()) readonly @endif/>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.tax_name') }}</strong>
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="input-group col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            {{ $booking->currency->code }}
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control input-money" name="booking[tax]" value="{{ old('tax', moneyDecimal($booking->tax)) }}" inputmode="numeric" style="text-align: right;"  @if ($booking->isCanceled()) readonly @endif>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.discount_name') }}</strong>
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="input-group col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            {{ $booking->currency->code }}
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control input-money" name="booking[discount]" value="{{ old('discount', moneyDecimal($booking->discount)) }}" inputmode="numeric" style="text-align: right;"  @if ($booking->isCanceled()) readonly @endif>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>

                                <tr class="card-footer">
                                    <td colspan="12">

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">{{ __('resources.bookings.model.gross_total') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">{{ $booking->currency->code }} {{ moneyDecimal($sumProducts) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    ***
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">+ {{ __('resources.bookings.model.taxes') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">+ {{ $booking->currency->code }} {{ moneyDecimal($booking->tax) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    ***
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">- {{ __('resources.bookings.model.discount') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">- {{ $booking->currency->code }} {{ moneyDecimal($booking->discount) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    ***
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">- {{ __('resources.bookings.model.discount_promocode') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">- {{ $booking->currency->code }} {{ moneyDecimal($booking->discount_promocode) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    @if($booking->promocode != null) {{$booking->promocode->code}} - {{$booking->promocode->name}} @endif
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">- {{ __('resources.bookings.model.discount_promocode') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">- {{ $booking->currency->code }} {{ moneyDecimal($booking->discount_promocode_provider) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                @if($booking->promocodeProvider != null) {{$booking->promocodeProvider->code}} - {{$booking->promocodeProvider->name}} @endif - PROVIDER
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">= {{ __('resources.label.statement_total') }}</span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right">{{ $booking->currency->code }} <strong>{{ moneyDecimal($sumProducts+$booking->tax-$booking->discount-$booking->discount_promocode-$booking->discount_promocode_provider) }}</strong></span>
                                                </div>
                                                <div class="form-group col-md-6">

                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">{{ __('resources.label.statement_receivable') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right @if(($booking->subtotal+$booking->tax) != $sumBills) label label-danger @endif">{{ $booking->currency->code }} {{ moneyDecimal($sumBills) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ __('resources.label.msg_statement_receivable') }}
                                                </div>
                                            </div>

                                            <div class="row" style="margin-bottom: -1.5%">
                                                <div class="form-group col-md-3">
                                                    <span class="text-left align-middle">{{ __('resources.label.statement_difference') }} </span>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <span class="pull-right @if($sumProducts != $booking->subtotal)label label-danger @endif">{{ $booking->currency->code }} {{ moneyDecimal($sumBills-($sumProducts+$booking->tax-$booking->discount-$booking->discount_promocode-$booking->discount_promocode_provider)) }}</span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ __('resources.label.msg_statement_difference') }}
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include("backend.bookings.partials.transactions")

    </div>
@endif
