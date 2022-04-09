<table id="promocodesTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.promocodes.model.name') }}</th>
            <th>{{ __('resources.promocodes.model.code') }}</th>
            <th>{{ __('resources.promocodes.model.discount_value') }}</th>
            <th class="text-center">{{ __('resources.promocodes.model.usages') }}</th>
            <th class="text-center">{{ __('resources.promocodes.model.expires_at') }}</th>
            <th class="text-center">{{ __('resources.payment-methods.name') }}</th>
            <th class="text-center">{{ __('resources.promocodes.model.max_installments') }}</th>
            <th class="text-center">{{ __('resources.promocodes.model.cancels_cash_discount') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($promocodeGroup->promocodes as $promocode)
        <tr>
            <td class="text-uppercase"><a href="{{ route('backend.promocodes.edit', [$promocodeGroup, $promocode]) }}">{{ $promocode->name }}</a></td>
            <td class="text-uppercase">{{ $promocode->code }}</td>
            <td class="text-uppercase">{{ money($promocode->discount_value, $promocode->currency->code) }}</td>
            <td class="text-uppercase text-center">{{ $promocode->usages }} / {{ $promocode->stock ?? '-' }}</td>
            <td class="text-uppercase text-center">{{ $promocode->expiresAtLabel ?? '-' }}</td>
            <td class="text-uppercase text-center">{{ $promocode->paymentMethod->name ?? __('messages.all') }}</td>
            <td class="text-uppercase text-center">{{ $promocode->max_installments ?? '-' }}</td>
            <td class="text-uppercase text-center">
                {{ $promocode->cancelsCashDiscountLabel }}
            </td>
            <td class="text-center skip">
                <a href="{{ route('backend.promocodes.destroy', [$promocodeGroup, $promocode]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
