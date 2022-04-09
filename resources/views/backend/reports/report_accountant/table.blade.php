@include('backend.reports.report_accountant.filters')

<table id="reportTable" class="table full-color-table full-inverse-table hover-table">
    <thead>
        <tr>
            <th class="text-center"></th>
            <th class="text-center">{{__('report.loc')}}</th>
            <th class="text-center">{{__('report.cpf_vat')}}</th>
            <th class="text-center">{{__('report.name')}}</th>
            <th class="text-center">{{__('report.address')}}</th>
            <th class="text-center">{{__('report.number')}}</th>
            <th class="text-center">{{__('report.complement')}}</th>
            <th class="text-center">{{__('report.neighbor')}}</th>
            <th class="text-center">{{__('report.tax_code')}}</th>
            <th class="text-center">{{__('report.uf')}}</th>
            <th class="text-center">{{__('report.zip')}}</th>
            <th class="text-center">{{__('report.sale_value')}}</th>
            <th class="text-center">{{__('report.pax')}}</th>
        </tr>
    </thead>
    @foreach($bookings as $booking)
        @php
            $city = app(App\Models\City::class)->find($booking->client->address_city);
        @endphp
        <tr>
            <td class="text-center">{{$loop->iteration}}</td>
            <td class="text-center">
                <a href="{{ route('backend.bookings.edit', $booking->id) }}" class="btn-block" target="_blank">
                    <i class="fa fa-external-link"></i> {{ $booking->id }}
                </a>
            </td>
            <td class="text-center">{{ $booking->bookingClient->document}}</td>
            <td class="text-center">{{ $booking->bookingClient->name}}</td>
            <td class="text-center">{{ $booking->bookingClient->address}}</td>
            <td class="text-center">{{ $booking->bookingClient->address_number}}</td>
            <td class="text-center">{{ $booking->bookingClient->address_complement}}</td>
            <td class="text-center">{{ $booking->bookingClient->address_neighborhood}}</td>
            <td class="text-center">@if($city) {{$cities->tax_code}} @endif</td>
            <td class="text-center">{{$booking->bookingClient->address_state}}</td>
            <td class="text-center">@if($booking->bookingClient->address_country == App\Enums\Country::BRAZIL) {{ $booking->bookingClient->address_zip}}  @endif</td>
            <td class="text-center">{{ money($booking->total, $booking->currency->code)}}</td>
            <td class="text-center">{{$booking->bookingPassengers()->count()}}</td>
        </tr>
    @endforeach

</table>


@push('scripts')
<script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(function() {
            $('#reportTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
</script>
@endpush
