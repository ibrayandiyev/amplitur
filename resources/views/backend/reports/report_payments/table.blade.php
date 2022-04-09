@include('backend.reports.report_payments.filters')
<table id="bookingsTable" class="table full-color-table full-inverse-table hover-table">
    <thead>
        <tr>
            <th class="text-center">{{__('report.date')}}</th>
            <th class="text-center">{{__('report.loc')}}</th>
            <th class="text-center">{{__('report.gateway')}}</th>
            <th class="text-center">{{__('report.type')}}</th>
            <th class="text-center">{{__('report.amount')}}</th>
            <th class="text-center">{{__('report.pay_form')}}</th>
            <th class="text-center">{{__('report.installment')}}</th>
            <th class="text-center">{{__('report.authorization')}}</th>
            <th class="text-center">{{__('report.nsu')}}</th>
            <th class="text-center">{{__('report.return_code')}}</th>
            <th class="text-center">{{__('report.message_return')}}</th>
        </tr>
    </thead>
    @foreach($reports as $report)
    @php
        $autorization   = $nsu = $returnCode = $returnMessage = "-";
        $transactions   = $report->transactions;
        switch($report->paymentMethod->type){
            case "credit":
                if($transactions->first()){
                    $_payload   = json_decode($transactions->first()->payload, true);
                    if($_payload){
                        if(isset($_payload["authorizationCode"])){
                            $autorization   = $_payload["authorizationCode"];
                        }
                        if(isset($_payload["nsu"])){
                            $nsu            = $_payload["tid"];
                        }
                        if(isset($_payload["returnCode"])){
                            $returnCode     = $_payload["returnCode"];
                        }
                        if(isset($_payload["returnMessage"])){
                            $returnMessage  = $_payload["returnMessage"];
                        }
                    }
                }
            break;
        }
    @endphp
    <tr>
        <td class="text-center">{{ $report->created_at->format("d/m/Y H:i:s")}}</td>
        <td width="8%" class="text-center">
            <a href="{{ route('backend.bookings.edit', $report->booking_id) }}" class="btn-block" target="_blank">
                <i class="fa fa-external-link"></i> {{ $report->booking_id }}
            </a>
        </td>
        <td class="text-center">{{ $report->processor }}</td>
        <td class="text-center">{{ $report->paymentMethod->type }}</td>
        <td class="text-center">{{ money($report->total, $report->booking->currency->code) }}</td>
        <td class="text-center">{{ $report->paymentMethod->name }}</td>
        <td class="text-center">{{ $report->installment }}</td>
        <td class="text-center">{{ $autorization }}</td>
        <td class="text-center">{{ $nsu }}</td>
        <td class="text-center">{{ $returnCode }}</td>
        <td class="text-center">{{ $returnMessage }}</td>
    </tr>
    @endforeach
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

