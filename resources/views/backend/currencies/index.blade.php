@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.currencies.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.currencies.name-plural') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="currenciesForm" method="post" action="{{ route('backend.currencies.update') }}">
                <div class="labelx label-service">
                    {{ __('resources.currencies.name-plural') }}
                    <div class="pull-right">
                        <span class="label label-light-info">Atualizado em {{ app(\App\Models\CurrencyQuotation::class)->lastUpdatedAt }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_method" value="put"/>
                    @csrf
                    <table id="currenciesTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('resources.currencies.model.name') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.currencies.model.quotation') }}</th>
                                <th class="text-center" width="20%">{{ __('resources.currencies.model.spread') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.currencies.model.spreaded_quotation') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencies as $currency)
                                    <tr class="table-active">
                                        <td colspan="5" class="font-bold">{{ $currency->code }}</td>
                                    </tr>
                                @foreach($currency->quotations as $quotation)
                                    @if (!$quotation->isSame())
                                        <tr>
                                            <td class="align-middle"><a href="{{ route('backend.currencies.edit', $quotation->id) }}" data-put>{{ $quotation->name }}</a></td>
                                            <td class="text-center align-middle">{{ decimal($quotation->quotation) }}</td>
                                            <td class="text-center skip">
                                                <input type="number" step="0.01" class="form-control" name="currency[{{ $quotation->id }}][spread]" value="{{ $quotation->decimalSpread }}" />
                                            </td>
                                            <td class="text-center">{!! $quotation->spreadedQuotation !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.currencies.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('metas')
    <meta name="csrf_token" content="{{ csrf_token() }}">
@endpush
