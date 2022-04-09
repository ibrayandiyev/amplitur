@if (user()->canManageBookingDetails())
    <div class="row">
        <div class="col-md-12">
            <div class="card border-rounded">
                <div class="card-header text-dark align-middle">
                    <strong>{{ __('resources.bookings.bills.transactions_name_plural') }}</strong>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <th width="5%" class="text-center">{{ __('resources.bookings.bills.model_tid') }}</th>
                            <th width="22%">{{ __('resources.bookings.bills.gateway') }}</th>
                            <th width="13%" class="text-center">{{ __('resources.bookings.bills.model_value') }}</th>
                            <th width="13%" class="text-center">{{ __('resources.bookings.bills.status') }}</th>
                            <th width="12%" class="text-center"></th>
                        </thead>
                        <tbody>
                            @foreach ($booking->bookingBills as $bookingBill)
                            @php
                            $total_refunded = 0;
                            @endphp
                                @foreach($bookingBill->transactions()->get() as $transaction)
                                @php
                                    $tid = "-";
                                    $canBeRefunded = $bookingBill->canBeRefunded();
                                    switch($transaction->gateway){
                                        case App\Enums\Processor::CIELO:
                                            $_processor_data    = json_decode($transaction->payload);
                                            $_processor_data    = json_decode($transaction->payload);
                                            $tid                = isset($_processor_data->tid)?$_processor_data->tid:null;
                                            break;
                                        default:
                                            break;
                                    }

                                @endphp
                                    <tr>
                                        <td class="text-center align-middle">{{ $transaction->gateway }}</td>
                                        <td class="text-center align-middle">
                                            {{ $tid }}
                                        </td>
                                        <td>
                                        {{ $booking->currency->code }} {{ moneyDecimal($transaction->amount) }}
                                        </td>
                                        <td>
                                        {{ ($transaction->status) }}
                                        </td>
                                        <td class="align-middle">
                                        {{$transaction->operation}}
                                            @switch($transaction->operation)
                                                @case ('payment'):
                                                    @if($canBeRefunded)
                                                        @push('paymentCancel')
                                                        <form id="cancelPayment{{$loop->index}}"  method="post" action="{{ route('backend.reports.booking_bills.cancel_bill', $bookingBill) }}" enctype="multipart/form-data" autocomplete="off">
                                                            <input type="hidden" name="_method" value="post" />
                                                            <input type="hidden" class="value_bill_cancel_{{$loop->index}}" name="total" />
                                                            <input type="hidden" class="transaction{{$loop->index}}" name="transaction_id" value="{{$transaction->id}}" />

                                                            @csrf

                                                        </form>
                                                        @endpush
                                                        <input type="text" class="form-control input-money value_cancel_{{$loop->index}}"  name="value_cancel" value="" maxValue="{{$transaction->amount}}">

                                                        <button type="submit" index="{{$loop->index}}" class="btn btn-primary btn-sm cancel-payment">
                                                            <i class="fa fa-block"></i> {{ __('messages.cancel_payment') }}
                                                        </button>
                                                    @endif
                                                @break;
                                                @case ('refund')
                                                @php
                                                    $total_refunded+= $transaction->amount
                                                @endphp
                                                <label>Total Refunded: {{ $booking->currency->code }} {{ moneyDecimal($total_refunded) }}</label>
                                                @break;
                                                @case ('fail')
                                                @case ('default')
                                                @php
                                                    $_payload = json_decode($transaction->payload);
                                                    $message  = isset($_payload->message)?$_payload->message:null;
                                                @endphp
                                                @if($message != "")
                                                    <label>Message: {{ $message }}</label>
                                                @endif
                                                @break;
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    $(document).ready(function () {
        $(document).on("click", ".cancel_payment", App.showSaveWarning);
    });
</script>
@endpush

