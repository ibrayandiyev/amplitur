

RELATÓRIO ESTOQUE <br>
- VISÃO MASTER<BR>
- SOMENTE ITENS COM BOOKING CONFIRMADO<BR>
@php
    $master = user()->isMaster();
@endphp
@include('backend.reports.report_stock.filters')
<table id="bookingsTable" class="table full-color-table full-inverse-table hover-table">
    <thead>
        <tr>
            <th class="text-center">{{__('report.service')}}</th>
            @if($master)
            <th class="text-center">{{__('report.provider')}}</th>
            @endif
            <th class="text-center">{{__('report.company')}}</th>
            <th class="text-center">{{__('report.sold')}}</th>
            <th class="text-center">{{__('report.stock')}}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $provider_id = $_filter_params['company_id'] ?? null;
            $company_id  = $_filter_params['company_id'] ?? null;
            if($company_id >0){
                $offers->where("company_id", $company_id);
            }
        @endphp
        @foreach($offers as $offer)
            @foreach($offer->getProducts() as $product)
            @php
            $boardingLocation = (app(\App\Repositories\OfferRepository::class)->getProduct($product['offer'], $product['id']));
            $route = null;
            switch($product['type']){
                case (\App\Enums\OfferType::BUSTRIP):
                    $route = $boardingLocation->bustripRoute;
                break;
                case (\App\Enums\OfferType::SHUTTLE):
                    $route = $boardingLocation->shuttleRoute;
                break;
                case (\App\Enums\OfferType::HOTEL):
                    break;
                break;
                case (\App\Enums\OfferType::LONGTRIP):
                    if($boardingLocation != null){
                        $route = $boardingLocation->longtripRoute;
                    }
                    break;
            }
            @endphp
                <tr>
                    <td class="text-left">{{$offer->id}} - {!! $offer->typeLabel !!}
                    @if($route != null)
                    {{ $route->name }} -
                    {{ $product['title']}}
                    @endif
                    </td>
                    @if($master)
                    <td class="text-center">{{ $product['offer']->provider->name }}</td>
                    @endif
                    <td class="text-center">{{ $product['offer']->company->company_name }}</td>
                    <td class="text-center">


                    VENDIDO</td>
                    <td class="text-center">
                    @switch($product['type'])
                        @case (\App\Enums\OfferType::BUSTRIP)
                        {{ $route->capacity }}
                        @break
                        @case (\App\Enums\OfferType::SHUTTLE)
                        {{ $route->capacity }}
                        @break
                        @case (\App\Enums\OfferType::HOTEL)
                        @break
                        @case (\App\Enums\OfferType::LONGTRIP)
                        @break
                    @endswitch
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#bookingsTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush

