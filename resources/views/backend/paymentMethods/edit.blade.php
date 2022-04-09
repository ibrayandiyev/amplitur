@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.payment-methods.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.paymentMethods.index') }}">{{ __('resources.payment-methods.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $paymentMethod->name }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="paymentMethodForm" method="post" action="{{ route('backend.paymentMethods.update', $paymentMethod) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.payment-methods.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">
                                <strong>Name</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $paymentMethod->name) }}" />
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>Category</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="category">
                                <option value="national" @if (old('category', $paymentMethod->category) == 'national') selected @endif>Nacional</option>
                                <option value="international" @if (old('category', $paymentMethod->category) == 'international') selected @endif>Internacional</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>Tipo</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="type">
                                <option value="credit" @if (old('type', $paymentMethod->type) == 'credit') selected @endif>Crédito</option>
                                <option value="debit" @if (old('type', $paymentMethod->type) == 'debit') selected @endif>Débito</option>
                                <option value="cash" @if (old('type', $paymentMethod->type) == 'cash') selected @endif>Dinheiro</option>
                                <option value="check" @if (old('type', $paymentMethod->type) == 'check') selected @endif>Cheque</option>
                                <option value="billet" @if (old('type', $paymentMethod->type) == 'billet') selected @endif>Boleto</option>
                                <option value="invoice" @if (old('type', $paymentMethod->type) == 'invoice') selected @endif>Invoice</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Máx. Parcelas</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" min="1" step="1" name="max_installments" value="{{ old('max_installments', $paymentMethod->max_installments) }}" />
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>1ª em Boleto</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="first_installment_billet">
                                <option value="0" @if (old('first_installment_billet', $paymentMethod->first_installment_billet) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('first_installment_billet', $paymentMethod->first_installment_billet) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row m-t-40">
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Offline</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="offline">
                                <option value="0" @if (old('offline', $paymentMethod->offline) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('offline', $paymentMethod->offline) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Komerci</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="komerci">
                                <option value="0" @if (old('komerci', $paymentMethod->komerci) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('komerci', $paymentMethod->komerci) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Rede</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="rede">
                                <option value="0" @if (old('rede', $paymentMethod->rede) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('rede', $paymentMethod->rede) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Cielo</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="cielo">
                                <option value="0" @if (old('cielo', $paymentMethod->cielo) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('cielo', $paymentMethod->cielo) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Shopline</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="shopline">
                                <option value="0" @if (old('shopline', $paymentMethod->shopline) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('shopline', $paymentMethod->shopline) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>Paypal</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="paypal">
                                <option value="0" @if (old('komerci', $paymentMethod->paypal) == 0) selected @endif>{{ __('messages.no') }}</option>
                                <option value="1" @if (old('komerci', $paymentMethod->paypal) == 1) selected @endif>{{ __('messages.yes') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.paymentMethods.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
