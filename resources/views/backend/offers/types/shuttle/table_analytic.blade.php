@php
    $type = $dl->type;
    $offersFilter = $offers->where("type", $type);
@endphp
<div class="tab-pane {{$active}}" id="tab-tb{{ $dl->id }}-{{$dl->type}}">
    <table id="shuttle" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>{{ __('resources.shuttle-routes.name-plural') }}</th>
                <th>{{ __('resources.shuttle-routes-boarding.name-plural') }}</th>
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
                $offerShuttle = $offer->getProducts();
                $offerShuttle = $offerShuttle->toArray();

                array_multisort(array_column($offerShuttle, 'title'), SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $offerShuttle);
            @endphp
                @foreach($offerShuttle as $shuttle)
                    @php
                        $shuttleRoute = \App\Models\ShuttleRoute::find($shuttle['shuttle_route_id']);
                        $shuttleBoardingLocations = $shuttleRoute->shuttleBoardingLocations;
                        $boardingId = null;
                    @endphp
                    @foreach($shuttleBoardingLocations->sortBy("price") as $boardingLocation)
                        <tr>
                            <td width="20%" class="">
                                @if($boardingId != $boardingLocation->shuttle_route_id)
                                    <a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $shuttle['title']}}</a>
                                @endif
                            </td>
                            <td width="26%" class="">

                                    {{ $boardingLocation->boardingAtLabel }} <span class="fa fa-clock-o"></span>  {{ $boardingLocation->boardingAtTimeLabel }} - {{ $boardingLocation->address->complement }}
                    {{ city($boardingLocation->address->city) }} - {{ state($boardingLocation->address->contry, $boardingLocation->address->state)}}
                            </td>
                            <td width="10%" class="text-center"> {{ $boardingLocation->extendedPrice }} </td>
                            <td width="10%" class="text-center">{{ $boardingLocation->extendedValuePrice }}</td>
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
                        @php
                            $boardingId = $boardingLocation->shuttle_route_id;
                        @endphp
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {

        });
    </script>
@endpush
