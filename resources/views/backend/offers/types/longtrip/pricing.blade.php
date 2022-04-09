<table id="pricingTable" class="col-sm-12 display nowrap table table-striped table-bordered">
    <tbody>
        @forelse ($offer->longtripRoutes as $longtripRoute)

            <tr class="colored-row">
                <td colspan="6" class="">Rota: {{ $longtripRoute->name }} </td>
            </tr>
            @php
            $fields         = $longtripRoute->fields;
            $string_dates   = "";
            @endphp

            @if(is_array($fields['sale_dates']))
                @foreach($fields['sale_dates'] as $key => $f)
                    @php
                        $date = \Carbon\Carbon::createFromFormat("Y-m-d", $f);
                        $string_dates .= $date->format("d/m/Y");
                        if(!$loop->last){
                            $string_dates .= ", ";
                        }
                    @endphp
                @endforeach
            @endif

            <tr style='background-color: #F37878;color:#fff;'>
                <td colspan="6" ><strong>{{ __('resources.longtrip-routes.model.fields.event_day') }}:</strong> {{ $string_dates }}</td>
            </tr>
            <tr style='background-color: #FFE789;color:#000;'>
                <td colspan="6" ><strong>{{ __('resources.longtrip-routes-boarding.name-plural') }}:</strong> {{ $longtripRoute->BoardingLocationListLine }}</td>
            </tr>
            <tr>
                <td width="50%"><strong>{{ __('resources.longtrip-routes.model.type_room') }}</strong></td>
                <td width="15%" class="text-center"><strong>{{ __('resources.longtrip-routes.model.price_net') }}</strong></td>
                <td width="20%" class="text-center"><strong>{{ __('resources.longtrip-routes.model.sale_price') }}</strong></td>
                <td width="15%" class="text-center"><strong>{!! __('resources.longtrip-routes.model.stock') !!}</strong></td>
            </tr>

            @php
                $longtripAccommodationsPricings = $longtripRoute->longtripAccommodationsPricings()->orderBy("price", "ASC")->get()->groupBy("longtrip_accommodation_type_id");
            @endphp

            @if (count($longtripAccommodationsPricings) > 0)

                    @foreach ($longtripAccommodationsPricings as $longtripAccommodationsPricingType)
                        @foreach ($longtripAccommodationsPricingType as $longtripAccommodationsPricing)
                        <tr>
                            <td class=" align-middle">
                            {{ $longtripAccommodationsPricingType[0]->type->name }}
                                <input type="hidden" name="longtrip_accommodations_pricing[{{ $longtripAccommodationsPricing->id }}][id]" value="{{ $longtripAccommodationsPricing->id }}" />
                                <input type="hidden" name="longtrip_accommodations_pricing[{{ $longtripAccommodationsPricing->id }}][longrip_accommodation_type_id]" value="{{ $longtripAccommodationsPricing->longrip_accommodation_type_id }}" />
                                <input type="hidden" name="longtrip_accommodations_pricing[{{ $longtripAccommodationsPricing->id }}][longtrip_route_id]" value="{{ $longtripRoute->id }}" />
                            </td>
                            <td class="align-middle">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            {{ $offer->currency }}
                                        </span>
                                    </div>
                                    <input type="text" class="form-control input-money" data-bookable-price name="longtrip_accommodations_pricing[{{ $longtripAccommodationsPricing->id }}][price]" placeholder="0,00" value="{{ money($longtripAccommodationsPricing->price) }}" />
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align: middle">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            {{ $offer->currency }}
                                        </span>
                                    </div>
                                    <span class="form-control receive-span sale-price" data-bookable-receive-price>{{ $longtripAccommodationsPricing->receiveablePrice }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <input type="number" min="0" step="1" class="form-control" name="longtrip_accommodations_pricing[{{ $longtripAccommodationsPricing->id }}][stock]" placeholder="0" value="{{ $longtripAccommodationsPricing->stock }}" />
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                @foreach ($longtripRoute->longtripAccommodationsTypes as $longtripAccommodationType)
                @php
                    $index = 100000 + rand(0,1000);
                @endphp
                    <tr>
                        <td class=" align-middle">
                            {{ $longtripAccommodationType->name }}
                            <input type="hidden" name="longtrip_accommodations_pricing[{{ $index }}][longtrip_accommodation_type_id]" value="{{ $longtripAccommodationType->id }}" />
                            <input type="hidden" name="longtrip_accommodations_pricing[{{ $index }}][longtrip_route_id]" value="{{ $longtripRoute->id }}" />
                        </td>
                        <td class="align-middle">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $offer->currency }}
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" data-bookable-price name="longtrip_accommodations_pricing[{{ $index }}][price]" placeholder="0,00" value="0.00" />
                            </div>
                        </td>
                        <td class="align-middle">
                            <span>{{ $offer->currency }}</span> <span data-bookable-receive-price>0,00</span>
                        </td>
                        <td class="align-middle">
                            <input type="number" min="0" step="1" class="form-control" name="longtrip_accommodations_pricing[{{ $index }}][stock]" placeholder="0" value="0" />
                        </td>
                    </tr>

                @endforeach
            @endif
        @empty
            {{__('resources.no_routes')}}
        @endforelse
    </tbody>
</table>
