<table id="bookingsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">{{ __('resources.bookings.name') }}</th>
            <th class="text-center">{{ __('resources.events.name') }}</th>
            <th class="text-center">{{ __('resources.bookings.model.starts_at') }}</th>
            <th width="5%" class="text-center">{{ __('resources.bookings.model.status') }}</th>
            <th width="12%" class="text-center">{{ __('resources.bookings.model.payment_status') }}</th>
            <th width="12%" class="text-center">{{ __('resources.bookings.model.document_status') }}</th>
            <th width="12%" class="text-center">{{ __('resources.bookings.model.voucher_status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td class="text-uppercase text-center">
                    <a href="{{ route('backend.bookings.edit', $booking->id) }}" class="btn-block" target="_blank">
                        <i class="fa fa-external-link"></i> {{ $booking->id }}
                    </a>
                </td>
                <td class="text-uppercase text-center">{{ $booking->package->name }}</td>
                <td class="text-uppercase text-center">{{ $booking->startsAtLabel }}</td>
                <td class="text-lowercase text-center">{!! $booking->statusLabel !!}</td>
                <td class="text-lowercase text-center">{!! $booking->paymentStatusLabel !!}</td>
                <td class="text-lowercase text-center">{!! $booking->documentStatusLabel !!}</td>
                <td class="text-lowercase text-center">{!! $booking->voucherStatusLabel !!}</td>
            </tr>
        @endforeach
    </tbody>
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
