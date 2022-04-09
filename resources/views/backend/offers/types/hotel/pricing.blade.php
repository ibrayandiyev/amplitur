<table id="pricingTable" class="col-sm-12 display nowrap table table-striped table-bordered">
    <tbody>
        @foreach ($hotelAccommodations as $hotelAccommodation)
            <tr class="colored-row">
                <td colspan="6" class="">{{ $hotelAccommodation->typeLabel }}</td>
            </tr>
            <tr>
                <td width="20%" class="text-center">{{ __('resources.hotels.model.checkin') }}</td>
                <td width="20%" class="text-center">{{ __('resources.hotels.model.checkout') }}</td>
                <td width="10%" class="text-center">{{ __('resources.hotels.model.nights_required') }}</td>
                <td width="20%" class="text-center">{{ __('resources.hotels.model.price_net') }}</td>
                <td width="20%" class="text-center">{{ __('resources.hotels.model.sale_price') }}</td>
                <td width="10%" class="text-center">{!! __('resources.hotels.model.stock') !!}</td>
            </tr>
            @php
                $pricings = $hotelAccommodation->hotelAccommodationsPricings()->get();
            @endphp

            @if (count($pricings) > 0)
                {{-- Already registred pricing --}}

                @foreach($pricings as $pricing)
                    <tr @if ($pricing->isRequired()) class="colored-required-row" @endif>
                        <td style="vertical-align: middle" class="text-center">
                            <input type="hidden" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][checkin]" value="{{ $pricing->checkin->format('Y-m-d') }}" />
                            <span>{{ formatDate($pricing->checkin) }}</span>
                        </td>
                        <td style="vertical-align: middle" class="text-center">
                            <input type="hidden" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][checkout]" value="{{ $pricing->checkout->format('Y-m-d') }}" />
                            <span>{{ formatDate($pricing->checkout) }}</span>
                        </td>
                        <td class="text-center">
                            <select class="form-control" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][required_overnight]">
                                <option value="1" @if ($pricing->required_overnight == 1) selected @endif>{{ __('messages.yes') }}</option>
                                <option value="0" @if ($pricing->required_overnight == 0) selected @endif>{{ __('messages.no') }}</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $offer->currency }}
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" data-bookable-price name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][price]" placeholder="0,00" value="{{ money($pricing->price) }}" />
                            </div>
                        </td>
                        <td class="text-center" style="vertical-align: middle">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    {{ $offer->currency }}
                                </span>
                                </div>
                                <span class="form-control receive-span sale-price" data-bookable-receive-price>{{ $pricing->receiveablePrice }}
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="number" min="0" step="1" class="form-control" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][stock]" placeholder="0" value="{{ $pricing->stock }}" />
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- A blank pricing table --}}
                @foreach($offer->package->bookablePeriod as $date)
                    <tr @if ($date['required']) class="colored-required-row" @endif>
                        <td>
                            <input type="hidden" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][checkin]" value="{{ $date['date']->format('Y-m-d') }}" />
                            <span>{{ formatDate($date['date']) }}</span>
                        </td>
                        <td>
                            <input type="hidden" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][checkout]" value="{{ tomorrow($date['date'])->format('Y-m-d') }}" />
                            <span>{{ formatDate(tomorrow($date['date'])) }}</span>
                        </td>
                        <td class="text-center">
                            <select class="form-control" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][required_overnight]">
                                <option value="1" @if (old('pricing.'. $hotelAccommodation->id .'.'. $loop->index .'required_overnight') == 0) selected @endif>{{ __('messages.yes') }}</option>
                                <option value="0" @if (old('pricing.'. $hotelAccommodation->id .'.'. $loop->index .'required_overnight') == 0) selected @endif>{{ __('messages.no') }}</option>
                            </select>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $offer->currency }}
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" data-bookable-price name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][price]" placeholder="0,00" value="{{ old('pricing.'. $hotelAccommodation->id .'.'. $loop->index .'price') }}" />
                            </div>
                        </td>
                        <td>
                            <span>{{ $offer->currency }}</span> <span data-bookable-receive-price>0,00</span>
                        </td>
                        <td>
                            <input type="number" min="0" step="1" class="form-control" name="pricing[{{ $hotelAccommodation->id }}][{{ $loop->index }}][stock]" placeholder="0" value="{{ old('pricing.'. $hotelAccommodation->id .'.'. $loop->index .'stock') }}" />
                        </td>
                    </tr>
                @endforeach
            @endif


        @endforeach
    </tbody>
</table>
