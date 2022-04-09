@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.payment-methods.create') }}</h3>
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
                <a href="{{ route('backend.packages.edit', $package) }}">
                    {{ $package->getExtendedTitle() }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.payment-methods.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="packagePaymentMethodForm" method="post" action="{{ route('backend.packages.storePaymentMethod', $package) }}" autocomplete="off">
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
                        <div class="form-group col-md-2 @if($errors->has('processor')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.processors.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="processor">
                                @foreach (\App\Enums\Processor::toArray() as $processor)
                                    <option value="{{ $processor }}" @if (old('processor') == $processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('tax')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>Taxa</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        %
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" name="tax" value="{{ old('tax', 0) }}" />
                            </div>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('discount')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>Desc. à vista</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        %
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" name="discount" value="{{ old('discount', 0) }}" />
                            </div>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('limiter')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>Limitador</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="limiter" value="{{ old('limiter', 7) }}" />
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('first_installment_billet')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>1ª em Boleto</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control form-control-sm" name="first_installment_billet">
                                <option value="1" @if (old('first_installment_billet_method_id') == 1) selected @endif>{{ __('messages.yes') }}</option>
                                <option value="0" @if (old('first_installment_billet_method_id') == 0) selected @endif>{{ __('messages.no') }}</option>
                            </select>
                            <select class="form-control form-control-sm" name="first_installment_billet_method_id">
                                <option value></option>
                                @foreach ($billetPaymentMethods as $billetPaymentMethod)
                                    <option value="{{ $billetPaymentMethod->id }}" @if (old('first_installment_billet_method_id') == $billetPaymentMethod->id) selected @endif>{{ lastWord($billetPaymentMethod->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('max_installments')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>Parcelas</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="max_installments" value="{{ old('max_installments', 12) }}" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.packages.edit', $package) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
