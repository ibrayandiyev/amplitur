@php
$type = $dl->type;
$offersFilter = $offers->where("type", $type);

@endphp


<br>
<div class="tab-pane {{$active}}" id="tab-tb{{ $dl->id }}-{{$dl->type}}">
    <table id="bus-trip" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>{{ __('resources.bustrip-routes.name-plural') }}</th>
                <th>{{ __('resources.bustrip-routes-boarding.name-plural') }}</th>
                <th class="text-center">{{ __('resources.net_rate') }}</th>
                <th class="text-center">{{ __('resources.sale_rate') }}</th>
                <th>{{ __('resources.providers.name') }}</th>
                <th>{{ __('resources.companies.name') }}</th>
                <th class="text-center">{{ __('resources.offers.model.status') }}</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            @foreach($offersFilter as $offer)
            @php
            $offerData = $offer->getProducts();
            $offerData = $offerData->toArray();

            array_multisort(array_column($offerData, 'title'), SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $offerData);
            $bustripRoutes = $offer->bustripRoutes
            @endphp
            @foreach ($bustripRoutes as $route)
            <tr>
                <td width="20%" class=""><a
                        href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{
                        $route->name }}</a></td>
                <td width="26%" class="">{!! $route->getBoardingLocationsCities(['order' => 'price']) !!}</td>
                <td width="10%" class="text-center">{!! $route->getBoardingLocationsSaleprice(['order' => 'price']) !!}</td>
                <td width="10%" class="text-center">{!! $route->getBoardingLocationsNetprice(['order' => 'price']) !!}</td>
                <td width="12%" class="">{{ $offer->provider->name }}</td>
                <td width="12%" class="">{{ $offer->company->company_name }}</td>
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
            @endforeach
        </tbody>
    </table>
</div>

