@php
    $type = $dl->type;
    $offersFilter = $offers->where("type", $type);
@endphp
<div class="tab-pane {{$active}}" id="tab-tb{{ $dl->id }}-{{$dl->type}}">
    <table  id="hotel" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>{{ __('resources.hotels.name') }}</th>
                <th>{{ __('resources.hotel-accommodations.name-plural') }}</th>
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
                <tr>
                    <td width="20%" class=""><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ mb_strtoupper($offer->hotelOffer->hotel->name) }}</a></td>
                    <td width="26%" class="">{!! $offer->hotelOffer->getAccommodations() !!}</td>
                    <td width="10%" class="text-center">{!! $offer->hotelOffer->getMinAccommodationsPricingsPrice() !!}</td>
                    <td width="10%" class="text-center">{!! $offer->hotelOffer->getMinAccommodationsPricingsNetprice() !!} </td>
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
