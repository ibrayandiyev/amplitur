@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.booking-bills.name') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.index') }}">
                    {{ __('resources.bookings.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.edit', $booking) }}">
                    {{ $booking->id }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.booking-bills.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="bookingBillForm" method="post" action="{{ route('backend.bookings.storeBill', $booking) }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.booking-vouchers.name') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4 @if($errors->has('payment_method_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.payment-methods.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="payment_method_id" style="width: 100%;">
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
                        <div class="form-group col-md-2 @if($errors->has('total')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-bills.model.total') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $booking->currency->code }}
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right input-money" name="total" value="{{ old('total') }}" inputmode="numeric" />
                            </div>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('tax')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-bills.model.tax') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $booking->currency->code }}
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right input-money" name="tax" value="{{ old('tax') }}" inputmode="numeric" />
                            </div>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('installment')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-bills.model.installment') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control text-center p-0 p-l-2" name="installment" value="{{ old('installment', $nextInstallment) }}" />
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('ct')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-bills.model.ct') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control text-center p-0 p-l-2" name="ct" value="{{ old('ct', $nextCt) }}" />
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('processor')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.processors.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control p-0 pl-2" name="processor" style="width: 100%;">
                                @foreach (\App\Enums\Processor::toArray() as $processor)
                                    <option value="{{ $processor }}" @if (old('processor') == $processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('expires_at')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-bills.model.expires_at') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" maxlength="10" class="form-control datepicker" name="expires_at" value="{{ old('expires_at') }}" placeholder="__/__/____" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.bookings.edit', $booking) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
