@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-7">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('navigation.financial') }}</li>
            <li class="breadcrumb-item active">{{ __('navigation.financial-bills') }}</li>
        </ol>
    </div>
    <div class="col-md-5">
        <div class="float-right">
            <button class="btn btn-sm btn-warning toggle-filter">
                <i class="fa fa-filter"></i>
                {{ __('messages.filter') }}
            </button>
        </div>
    </div>
</div>

@include('backend.financial.bills-filters')

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="pa-10 bg-success">
                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0">{{ money($totals['paid'], \App\Enums\Currency::REAL) }}</h3>
                    <h5 class="text-muted m-b-0">Recebido</h5></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="d-flex flex-row">
                <div class="pa-10 bg-warning">
                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                <div class="align-self-center m-l-20">
                    <h3 class="m-b-0">{{ money($totals['pending'], \App\Enums\Currency::REAL) }}</h3>
                    <h5 class="text-muted m-b-0">A receber</h5></div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('navigation.financial-bills') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="billsTable" class="table table-hover table-striped m-0">
                        <thead>
                            <th width="10%" class="text-center">{{ __('resources.booking-bills.model.created_at') }}</th>
                            <th width="10%" class="text-center">{{ __('resources.booking-bills.model.expires_at') }}</th>
                            <th class="text-center">{{ __('resources.payment-methods.name') }}</th>
                            <th class="text-center">{{ __('resources.bookings.name') }}</th>
                            <th width="15%" class="text-center">{{ __('resources.booking-bills.model.total') }}</th>
                            <th width="8%" class="text-center">{{ __('resources.booking-bills.model.installment') }}</th>
                            <th width="10%" class="text-center">{{ __('resources.processors.name') }}</th>
                            <th width="10%" class="text-center">{{ __('resources.booking-bills.model.status') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($bookingBills as $bookingBill)
                                <tr>
                                    <td class="text-center">
                                        {{ $bookingBill->createdAtLabel }}
                                    </td>
                                    <td class="text-center">
                                        {{ $bookingBill->expiresAtLabel }}
                                    </td>
                                    <td class="text-center">
                                        {{ $bookingBill->paymentMethod->name }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('backend.bookings.edit', $bookingBill->booking->id) }}" class="btn-block" target="_blank">
                                            <i class="fa fa-external-link"></i> {{ $bookingBill->booking->id }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ money($bookingBill->total, \App\Enums\Currency::REAL) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $bookingBill->installment }}
                                    </td>
                                    <td class="text-center">
                                        {{ $bookingBill->processor }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {!! $bookingBill->statusLabel !!}
                                        @if ($bookingBill->isPaid())
                                            <span class="label label-light-success">{{ $bookingBill->paid_at->format('d/m/Y') }}</span>
                                        @elseif ($bookingBill->isCanceled())
                                            <span class="label label-light-danger">{{ $bookingBill->canceled_at->format('d/m/Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#billsTable').DataTable({
                searching: false,
                order: [[0, 'desc']],
            });
        });
    </script>
@endpush
