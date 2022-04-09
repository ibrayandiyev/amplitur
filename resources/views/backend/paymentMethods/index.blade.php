@extends('backend.template.default')
@section('content')
@php
    if($tab == null){
        $tab = "list";
    }
    $activeList = $activeTemplate = "";
    switch($tab){
        default:
        case "list":
            $activeList = "active";
        break;
        case "template":
            $activeTemplate = "active";
        break;
    }
@endphp
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.payment-methods.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.payment-methods.name-plural') }}</li>
        </ol>
    </div>
</div>

<form id="paymentMethodsForm" action="{{ route('backend.paymentMethods.updateTemplate') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.payment-methods.name-plural') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$activeList}}" data-toggle="tab" href="#list" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.payment-methods.list') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$activeTemplate}}" data-toggle="tab" href="#template" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">Template</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane {{ $activeList }}" id="list" role="tab-panel">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="paymentMethodsTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('resources.payment-methods.name') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-success font-bold">
                                            <td colspan="2">Internacional</td>
                                        </tr>
                                        @foreach($paymentMethods['international'] as $paymentMethod)
                                            <tr>
                                                <td><a href="{{ route('backend.paymentMethods.edit', $paymentMethod) }}">{{ $paymentMethod->name }}</a></td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-warning font-bold">
                                            <td colspan="2">Nacional</td>
                                        </tr>
                                        @foreach($paymentMethods['national'] as $paymentMethod)
                                            <tr>
                                                <td><a href="{{ route('backend.paymentMethods.edit', $paymentMethod) }}">{{ $paymentMethod->name }}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {{ $activeTemplate }}" id="template" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>Prazo limite para pagamento</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="package_template[payment_expire_days]" value="{{ old('package_template.payment_expire_days', $packageTemplate->payment_expire_days) }}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            dia(s)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong> </strong><BR>
                                    <span class="text-danger"></span>
                                </label>
                                <a href="{{ route('backend.paymentMethods.storeTemplate')}}" class="btn btn-rounded btn-block btn-outline-primary form-control">
                                    <i class="fa fa-plus"></i> {{ __('resources.add_payment_method_template') }}
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <th class="tex-tencer" width="20%">Forma de Pagamento</th>
                                        <th class="tex-tencer" width="20%">Processador</th>
                                        <th>Taxa</th>
                                        <th>Desc. à vista</th>
                                        <th class="tex-tencer" width="10%">Limitador</th>
                                        <th class="tex-tencer" width="10%">1ª em Boleto</th>
                                        <th class="tex-tencer" width="10%">Parcelas</th>
                                    </thead>
                                    <tbody>
                                        <tr class="table-success font-bold">
                                            <td colspan="8">Internacional</td>
                                        </tr>
                                        @foreach ($paymentMethodTemplates['international'] as $paymentMethodTemplate)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][id]" value="{{ $paymentMethodTemplate->id }}" />
                                                    {{ $paymentMethodTemplate->paymentMethod->name }}
                                                </td>
                                                <td>
                                                    <select class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][processor]">
                                                        @foreach (\App\Enums\Processor::toArray() as $processor)
                                                            <option value="{{ $processor }}" @if ($processor == $paymentMethodTemplate->processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                %
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control input-money" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][tax]" value="{{ moneyDecimal($paymentMethodTemplate->tax) ?? 0 }}" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                %
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control input-money" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][discount]" value="{{ moneyDecimal($paymentMethodTemplate->discount) ?? 0 }}" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][limiter]" value="{{ $paymentMethodTemplate->limiter ?? 0 }}" />
                                                </td>
                                                <td>
                                                    <select class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][first_installment_billet]">
                                                        <option value="1" @if ($paymentMethodTemplate->first_installment_billet == 1) selected @endif>{{ __('messages.yes') }}</option>
                                                        <option value="0" @if ($paymentMethodTemplate->first_installment_billet == 0) selected @endif>{{ __('messages.no') }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][max_installments]" value="{{ $paymentMethodTemplate->max_installments ?? 0 }}" />
                                                </td>
                                                <td>
                                                    <a href="{{ route('backend.paymentMethods.destroy', [$paymentMethodTemplate]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-warning font-bold">
                                            <td colspan="8">Nacional</td>
                                        </tr>
                                        @foreach ($paymentMethodTemplates['national'] as $paymentMethodTemplate)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][id]" value="{{ $paymentMethodTemplate->id }}" />
                                                    {{ $paymentMethodTemplate->paymentMethod->name }}
                                                </td>
                                                <td>
                                                    <select class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][processor]">
                                                        @foreach (\App\Enums\Processor::toArray() as $processor)
                                                            <option value="{{ $processor }}" @if ($processor == $paymentMethodTemplate->processor) selected @endif>{{ mb_strtoupper($processor) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                %
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control input-money" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][tax]" value="{{ $paymentMethodTemplate->tax ?? 0 }}" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                %
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control input-money" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][discount]" value="{{ $paymentMethodTemplate->discount ?? 0 }}" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][limiter]" value="{{ $paymentMethodTemplate->limiter ?? 0 }}" />
                                                </td>
                                                <td>
                                                    <select class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][first_installment_billet]">
                                                        <option value="1" @if ($paymentMethodTemplate->first_installment_billet == 1) selected @endif>{{ __('messages.yes') }}</option>
                                                        <option value="0" @if ($paymentMethodTemplate->first_installment_billet == 0) selected @endif>{{ __('messages.no') }}</option>
                                                    </select>
                                                    <select class="form-control form-control-sm" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][first_installment_billet_method_id]">
                                                        <option value></option>
                                                        @foreach ($billetPaymentMethods as $billetPaymentMethod)
                                                            <option value="{{ $billetPaymentMethod->id }}" @if ($paymentMethodTemplate->first_installment_billet_method_id == $billetPaymentMethod->id) selected @endif>{{ lastWord($billetPaymentMethod->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="payment_method_templates[{{ $paymentMethodTemplate->id }}][max_installments]" value="{{ $paymentMethodTemplate->max_installments ?? 0 }}" />
                                                </td>
                                                <td>
                                                    <a href="{{ route('backend.paymentMethods.destroy', [$paymentMethodTemplate]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.paymentMethods.index') }}"  class="btn btn-secondary">
                        <i class="fa fa-trash"></i> {{ __('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
