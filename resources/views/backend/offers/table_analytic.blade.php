@php
$dataList  = collect( $offers )->unique("type");
@endphp
<ul class="nav nav-tabs" role="tablist">

@foreach($dataList as $dl)
    @php
    $offersFilter = $offers->where("type", $dl->type);
    @endphp
    <li class="@if($loop->first) active show @endif nav-item "  >
    <a href="#tab-tb{{ $dl->id }}-{{$dl->type}}" class="nav-link" data-toggle="tab">{{ $dl->typeText}} ({{$offersFilter->count()}})</a>
    </li>
@endforeach
</ul>

@php
$active = "active";
@endphp
<div class="tab-content">
    @foreach($dataList as $dl)
        @php
        if(!$loop->first){
            $active = "";
        }
        @endphp
        @switch($dl->type)
            @case("hotel")
                @include('backend.offers.types.hotel.table_analytic', ['dl' => $dl, 'offers' => $offers, "active" => $active])
            @break
            @case("shuttle")
                @include('backend.offers.types.shuttle.table_analytic', ['dl' => $dl, 'offers' => $offers, "active" => $active])
            @break
            @case("bus-trip")
                @include('backend.offers.types.bus-trip.table_analytic', ['dl' => $dl, 'offers' => $offers, "active" => $active])
            @break
            @case("longtrip")
                @include('backend.offers.types.longtrip.table_analytic', ['dl' => $dl, 'offers' => $offers, "active" => $active])
            @break;
            @case("food")
            @php
                $additionalGroups = $dl->additionalGroups()->get()
            @endphp
                @include('backend.offers.types.food.table_analytic', ['dl' => $dl, 'offers' => $offers, "offer" => $dl, "active" => $active])
            @break;
            @case("ticket")
            @php
                $additionalGroups = $dl->additionalGroups()->get()
            @endphp
                @include('backend.offers.types.ticket.table_analytic', ['dl' => $dl, 'offers' => $offers, "offer" => $dl, "active" => $active])
            @break;
            @case("transfer")
            @php
                $additionalGroups = $dl->additionalGroups()->get()
            @endphp
                @include('backend.offers.types.transfer.table_analytic', ['dl' => $dl, 'offers' => $offers, "offer" => $dl, "active" => $active])
            @break;
            @case("travel-insurance")
            @php
                $additionalGroups = $dl->additionalGroups()->get()
            @endphp
                @include('backend.offers.types.travel-insurance.table_analytic', ['dl' => $dl, 'offers' => $offers, "offer" => $dl, "active" => $active])
            @break;
            @case("additional")
            @php
                $additionalGroups = $dl->additionalGroups()->get()
            @endphp
                @include('backend.offers.types.additional.table_analytic', ['dl' => $dl, 'offers' => $offers, "offer" => $dl, "active" => $active])
            @break;
            @default
                @php
                    $type = $dl->type;
                    $offersFilter = $offers->where("type", $type);
                @endphp

                <div class="tab-pane {{$active}}" id="tab-tb{{ $dl->id }}-{{$dl->type}}">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('resources.packages.name') }}</th>
                                <th>{{ __('resources.providers.name') }}</th>
                                <th>{{ __('resources.companies.name') }}</th>
                                <th class="text-center">{{ __('resources.offers.model.status') }}</th>
                                <th class="text-center">{{ __('resources.offers.model.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offersFilter as $offer)

                                <tr>
                                    <td class=""><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $offer->package->extendedName }}</a></td>
                                    <td class="">{{ $offer->provider->name }}</td>
                                    <td class="">{{ $offer->company->company_name }}</td>
                                    <td class="text-center">{!! $offer->statusLabel !!}</td>
                                    <td class="text-center skip">
                                        @if (user()->canDeleteOffer($offer))
                                            <a href="{{ route('backend.providers.companies.offers.destroy', [$offer->provider, $offer->company, $offer]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif

                                        @if (user()->canReplicateOffer($offer))
                                            <a href="{{ route('backend.offers.replicate', [$offer]) }}"  class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="{{ __('resources.offers.replicate') }}">
                                                <i class="fa fa-copy"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @break;

        @endswitch

        @push('scripts')
            <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
            <script type="text/javascript">
                $(function() {
                    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
                    } );
                });
            </script>
        @endpush
    @endforeach
</div>
