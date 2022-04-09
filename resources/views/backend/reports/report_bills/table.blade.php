@include('backend.reports.report_bills.filters')
@php
    $totals = null;
@endphp
<table id="bookingsTable" class="table full-color-table full-inverse-table hover-table">
        <thead>
            <tr>
                <th class="text-center">{{__('report.loc')}}</th>
                <th class="text-center">{{__('report.provider')}}</th>
                <th class="text-center">{{__('report.company')}}</th>
                <th class="text-center">{{__('report.sale_date')}}</th>
                <th class="text-center">{{__('report.due_date')}}</th>
                <th class="text-center">{{__('report.pay_form')}}</th>
                <th class="text-center">{{__('report.installment')}}</th>
                <th class="text-center">{{__('report.tax')}}</th>
                <th class="text-center">{{__('report.gateway')}}</th>
                <th class="text-center">{{__('report.booking_status')}}</th>
                <th class="text-center">{{__('report.payment_status')}}</th>
                <th class="text-center">{{__('report.payment_bill_status')}}</th>
                <th class="text-center">{{__('report.amount')}} </th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookingBills as $bookingBill)
                <tr>
                    <td class="text-center">
                        <a href="{{ route('backend.bookings.edit', $bookingBill->booking_id) }}" class="btn-block" target="_blank">
                            <i class="fa fa-external-link"></i> {{ $bookingBill->booking_id }}
                        </a>
                     </td>
                    <td class="text-center">{{ $bookingBill->booking->offer->provider->company_name}}</td>
                    <td class="text-center">{{ $bookingBill->booking->offer->company->company_name }}</td>
                    <td class="text-center">{{ $bookingBill->booking->createdAtLabel }}</td>
                    <td class="text-center">{{ $bookingBill->expiresAtLabel }}</td>
                    <td class="text-center">{{ $bookingBill->paymentMethod->name }}</td>
                    <td class="text-center">{{ $bookingBill->installment }}</td>
                    <td class="text-center">{{ $bookingBill->tax }}</td>
                    <td class="text-center">{{ $bookingBill->processor }}</td>
                    <td class="text-center">{!! $bookingBill->booking->status_label !!}</td>
                    <td class="text-center">{!! $bookingBill->booking->payment_status_label !!}</td>
                    <td class="text-center">{!! $bookingBill->status_label !!}</td>
                    <td class="text-center">{{ money($bookingBill->total, $bookingBill->booking->currency->code) }} </td>
                </tr>
                @php
                if(!isset($totals[$bookingBill->booking->currency->code])){
                    $totals[$bookingBill->booking->currency->code] = 0;
                }
                $totals[$bookingBill->booking->currency->code] += $bookingBill->total;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="10" class="text-right" ><strong>{{__('report.total_receive')}}: </strong></th>
                <td>@if(is_array($totals))
                        @foreach($totals as $key => $total)
                            {{ money($total, $key)}} <br/>
                        @endforeach
                    @else
                    -
                    @endif</td>
            </tr>
        </tfoot>
</table>


@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#bookingsTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush

