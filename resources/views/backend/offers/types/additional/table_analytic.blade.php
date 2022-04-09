    <div class="tab-pane {{$active}}" id="tab-tb{{ $dl->id }}-{{$dl->type}}">
        <table  id="additional" class="table table-bordered table-striped table-hover">
@foreach ($additionalGroups as $additionalGroup)

            <thead>
                @if($loop->first)
                <tr>
                    <th class="text-center">{{ __('resources.group') }}</th>
                    <th class="text-center">{{ __('resources.item') }}</th>
                    <th class="text-center">{{ __('resources.net_rate') }}</th>
                    <th class="text-center">{{ __('resources.sale_rate') }}</th>
                    <th class="text-center">{{ __('resources.view') }}</th>
                    <th>{{ __('resources.providers.name') }}</th>
                    <th>{{ __('resources.companies.name') }}</th>
                    <th class="text-center">{{ __('resources.offers.model.status') }}</th>
                    <th></th>
                </tr>
                @endif
            </thead>
            <tbody>
                @foreach ($additionalGroup->additionals as $additional)
                    <tr>
                            <td width="15%" >{{ $additionalGroup->name }}<br>{{$additionalGroup->internal_name}}</td>
                            <td width="30%"><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $additional->name }} {!! $additional->typeLabel !!}</a>
                            <td width="10%" class="text-center">{{ $additional->currency . ' ' . moneyDecimal($additional->getPriceNet()) }}</td>
                            <td width="10%" class="text-center" >{{ $additional->currency . ' ' . moneyDecimal($additional->getPrice()) }}</td>
                            <td width="5%" class="text-center">{!! $additional->availabilityLabel !!}</td>
                            <td width="10%" class="">{{$offer->provider->name }}</td>
                            <td width="10%" class="">{{$offer->company->company_name }}</td>
                            <td width="10%" class="text-center">{!! $offer->statusLabel !!}</td>
                            <td>
                                @if (user()->canReplicateOffer($offer))
                                <a href="{{ route('backend.offers.replicate', [$offer]) }}"  class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="{{ __('resources.offers.replicate') }}">
                                    <i class="fa fa-copy"></i>
                                </a>
                                @endif
                            </td>
                    </tr>
                @endforeach
            </tbody>
@endforeach

        </table>
    </div>
