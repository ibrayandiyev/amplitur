@foreach ($additionalGroups as $additionalGroup)
<h3 class="group-entity-subhead mt-10 mb-0 p-10">
    <i>{{ __('resources.label.internal_label') }} </i>: {{$additionalGroup->internal_name}} |
    <i>{{ __('resources.label.front_name') }} </i>: {{$additionalGroup->name}}
    <div class="pull-right">
        <a href="{{ route('backend.providers.companies.offers.ticket.editGroup', [$provider, $company, $offer, $additionalGroup]) }}" class="btn btn-sm btn-warning">
            <i class="fa fa-pencil"></i>
        </a>
        @if ($canChange)
        <a href="{{ route('backend.providers.companies.offers.ticket.destroyGroup', [$provider, $company, $offer, $additionalGroup]) }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir">
            <i class="fa fa-trash"></i>
        </a>
        @endif
    </div>
</h3>
<table id="additionalItems" class="table table-bordered table-striped table-hover additionals-table pricing-tab">
    <thead>
        <tr>
            <th width="30%">{{ __('resources.additionals.model.name') }}</th>
            <th class="text-center" width="8%">{{ __('resources.valid_to') }}</th>
            <th class="text-center" width="7%">{{ __('resources.additionals.model.view') }}</th>
            <th class="text-center" width="11%">{{ __('resources.additionals.model.price_net') }}</th>
            <th class="text-center" width="11%">{{ __('resources.additionals.model.sale_price') }}</th>
            <th class="text-center" width="6%">{{ __('resources.additionals.model.stock') }}</th>
            <th class="text-center" width="6%">{{ __('resources.additionals.model.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($additionalGroup->additionals as $additional)
        <tr data-href="{{ route('backend.providers.companies.offers.ticket.updateItem', [$provider, $company, $offer, $additional]) }}">
            <td><a href="{{ route('backend.providers.companies.offers.ticket.updateItem', [$provider, $company, $offer, $additional]) }}" data-put>{{ $additional->name }}</a>
            <input type="hidden" min="0" class="form-control" data-name="additional_id" name="additional_id[]" value="{{ $additional->id }}">
            </td>
            <td class="text-center day-show">
                {!! $additional->getSalesDatesFormattedAttribute() !!}
            </td>
            <td class=" text-center">
                {!! $additional->availabilityLabel !!}
            </td>
            <td class=" skip">
                <div class="input-group" style="width: 150px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            {{ $additional->currency }}
                        </span>
                    </div>
                    <input type="text" class="form-control input-money" data-bookable-price data-name="price" name="price[]" value="{{ moneyDecimal($additional->price) }}" inputmode="numeric" style="text-align: right;">
                </div>
            </td>
            <td class=" skip">
                <div class="input-group" style="width: 150px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            {{ $additional->currency }}
                        </span>
                    </div>
                    <span class="form-control receive-span sale-price" data-bookable-receive-price value="{{ moneyDecimal($additional->price) }}" />
                </div>
            </td>
            <td class=" skip">
                <input type="number" min="0" class="form-control" data-name="stock" name="stock[]" value="{{ $additional->stock }}">
            </td>
            <td class="text-center skip">
                <a href class="btn btn-sm btn-primary save-put" data-toggle="tooltip" data-placement="top" title="{{ __('messages.save') }}">
                    <i class="fa fa-save"></i>
                </a>
                @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.ticket.destroyItem', [$provider, $company, $offer, $additional]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir">
                    <i class="fa fa-trash"></i>
                </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach
@push('scripts')
<script src="/backend/js/resources/pricing.hotel.js"></script>
@endpush
@push('metas')
    <meta name="csrf_token" content="{{ csrf_token() }}">
@endpush
