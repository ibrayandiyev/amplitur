@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.promocode-groups.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.promocodes.index') }}">{{ __('resources.promocodes.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">{{ __('resources.promocode-groups.name-plural') }}</li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.promocodes.groups.edit', $promocodeGroup) }}">
                   {{ $promocodeGroup->name }}
                </a>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="promocodeGroupForm" method="post" action="{{ route('backend.promocodes.store', $promocodeGroup) }}" autocomplete="off">
                @csrf

                <div class="labelx label-service">
                    {{ __('resources.promocode-groups.info') }}
                </div>

                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-8 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('code')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.code') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="code" value="{{ old('code') }}" required>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('currency_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.currencies.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="currency_id">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" @if (old('currency_id') == $currency->id) selected @endif>{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('discount_value')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.discount_value') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase input-money" name="discount_value" value="{{ old('discount_value') }}" required>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('stock')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.stock') }}</strong>
                                </label>
                                <input type="number" min="0" class="form-control text-uppercase" name="stock" value="{{ old('stock') }}" required>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('expires_at')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.expires_at') }}</strong>
                                </label>
                                <input type="text" maxlength="10" class="form-control text-uppercase datepicker" name="expires_at" value="{{ old('expires_at') }}" placeholder="__/__/____" required>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('payment_method_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.payment-methods.name') }}</strong>
                                </label>
                                <select class="form-control select2" name="payment_method_id">
                                    <option value>{{ __('messages.all') }}</option>
                                    <optgroup label={{ __('messages.national') }}>
                                        @foreach ($paymentMethods['national'] as $paymentMethod)
                                            <option value="{{ $paymentMethod->id }}" @if (old('payment_method_id') == $paymentMethod->id) selected @endif>{{ $paymentMethod->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label={{ __('messages.international') }}>
                                        @foreach ($paymentMethods['international'] as $paymentMethod)
                                            <option value="{{ $paymentMethod->id }}" @if (old('payment_method_id') == $paymentMethod->id) selected @endif>{{ $paymentMethod->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('max_installments')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.max_installments') }}</strong>
                                </label>
                                <input type="number" min="0" class="form-control text-uppercase" name="max_installments" value="{{ old('max_installments') }}" required>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('cancels_cash_discount')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.promocodes.model.cancels_cash_discount') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="cancels_cash_discount">
                                    <option value="0" @if (old('cancels_cash_discount') == 0) selected @endif>{{ __('messages.no') }}</option>
                                    <option value="1" @if (old('cancels_cash_discount') == 1) selected @endif>{{ __('messages.yes')  }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.promocodes.index') }}" class="btn btn-secondary">
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
    @include('backend.template.scripts.select-events')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
    </script>
@endpush
