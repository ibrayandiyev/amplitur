<div class="tab-pane {{ $activeProducts }}" id="products" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            @if(user()->isMaster())

                <div class="card border-rounded">
                    <div class="card-header text-dark align-middle">
                        <strong>{{ __('resources.label.booking_offer') }}</strong>
                    </div>

                    <div class="card-body p-0">
                        <table class="table-hover table-striped col-sm-12">
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2" style="width: 500px;" name="booking[product_type]">
                                            @foreach (App\Enums\OfferType::classTypeToArray() as $offerType)
                                                <option
                                                    value="{{ $offerType }}"
                                                    @if ($booking->product_type == $offerType )
                                                        selected
                                                    @endif>
                                                    {{ \App\Enums\OfferType::getClassTypeNameTranslation($offerType) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="card border-rounded">
                <div class="card-header text-dark align-middle">
                    <strong>{{ __('resources.bookings.main-service') }}</strong>
                    <a href="{{ route('backend.bookings.createProduct', $booking) }}" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-plus"></i>
                        {{ __('messages.add-item') }}
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table-hover table-striped col-sm-12">
                        <thead>
                            <tr>
                                <th class="text-center" width="30%">{{ __('resources.bookings.model.offer') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.booking-products.model.date') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.additionals.model.sale_price') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.additionals.model.price_net') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.additionals.model.currency') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.sale-coefficient.name') }}</th>
                                <th class="text-center" width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                ($packageProducts = $booking->package->getProducts(App\Enums\OfferType::CLASS_TYPE_OFFER_TYPE[$booking->product_type]));
                            
                                ($packageBookablePeriod = $booking->package->bookablePeriod);
                            @endphp
                            @foreach ($booking->bookingProducts as $key => $bookingProduct)
                                <tr>
                                    <td>
                                        <input type="hidden" name="bookingProducts[{{ $key }}][id]" value="{{ $bookingProduct->id }}" />
                                        <select class="form-control select2 selectService" data-objectClass="bp-{{$key}}" style="width: 500px;" name="bookingProducts[{{ $key }}][product_id]" @if ($booking->isCanceled()) disabled readonly @endif>
                                            @foreach ($packageProducts as $product)
                                                @php 
                                                    $_dates = null;
                                                    switch(get_class($product)){
                                                        case \App\Models\LongtripAccommodationsPricing::class:
                                                            $_dates = json_encode($product->longtripRoute->getBoardingLocationInitialDates());
                                                            break;
                                                        case \App\Models\ShuttleBoardingLocation::class:
                                                        case \App\Models\BustripBoardingLocation::class:
                                                        case \App\Models\LongtripBoardingLocation::class:
                                                            $_dates = json_encode([$product->boarding_at->format("d/m/Y")]);
                                                            break;
                                                        case \App\Models\HotelAccommodation::class:
                                                            $packageBookablePeriod = $booking->package->bookablePeriod;
                                                            if($packageBookablePeriod){
                                                                foreach($packageBookablePeriod as $period){
                                                                    $_dates[] = $period['date']->format('d/m/Y');
                                                                }
                                                                $_dates = json_encode($_dates);
                                                            }
                                                            break;
                                                        
                                                    }
                                                @endphp 
                                                <option
                                                    value="{{ $product->id }}"
                                                    data-dates="{{ $_dates }}"
                                                    @if ($bookingProduct->product_id == $product->id && $bookingProduct->product_type == get_class($product))
                                                        selected
                                                        date-selected="{{ ($bookingProduct->date)?$bookingProduct->date->format("d/m/Y"):''}}"
                                                    @endif>
                                                    {{ $product->getExtendedTitle() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control bp-{{$key}}" style="width: 200px;" name="bookingProducts[{{ $key }}][date]"  @if ($booking->isCanceled()) disabled readonly @endif>
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    {{ $booking->currency->code }}
                                                </span>
                                            </div>
                                            <input type="text" class="form-control input-money" style="text-align: right; width: 100px;" name="bookingProducts[{{ $key }}][price]" value="{{ old('bookingProduct.price', moneyDecimal($bookingProduct->price)) }}" inputmode="numeric" @if ($booking->isCanceled()) disabled readonly @endif>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    {{ $booking->currency->code }}
                                                </span>
                                            </div>
                                            <input type="text" class="form-control input-money" style="text-align: right; width: 100px;" name="bookingProducts[{{ $key }}][price_net]"   value="{{ moneyDecimal($bookingProduct->price_net) }}" inputmode="numeric" @if ($booking->isCanceled()) disabled readonly @endif>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                            {{ $bookingProduct->currencyOrigin->code }}
                                    </td>
                                    <td class="text-center align-middle">
                                            {{ $bookingProduct->sale_coefficient }}
                                    </td>

                                    <td class="text-center align-middle">
                                        @if (!$booking->isCanceled())
                                            <a href="{{ route('backend.bookings.destroyProduct', [$booking, $bookingProduct]) }}" class="btn btn-danger btn-sm delete pull-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card border-rounded">
                <div class="card-header text-dark align-middle">
                    <strong>{{ __('resources.bookings.additionals') }}</strong>
                    <a href="{{ route('backend.bookings.createAdditional', $booking) }}" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-plus"></i>
                        {{ __('messages.add-item') }}
                    </a>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="30%">{{ __('resources.additionals.name') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.booking-passengers.name') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.additionals.model.sale_price') }}</th>
                                <th class="text-center" width="15%">{{ __('resources.additionals.model.price_net') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.additionals.model.currency') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.sale-coefficient.name') }}</th>
                                <th class="text-center skip" width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($booking->bookingPassengerAdditionals ?? [] as $bookingAdditional)
                                <tr>
                                    <td>
                                        <input type="hidden" name="bookingPassengerAdditionals[{{ $bookingAdditional->id }}][id]" value="{{ $bookingAdditional->id }}" />
                                        <select class="form-control select2" style="width: 500px;" name="bookingPassengerAdditionals[{{ $bookingAdditional->id }}][additional_id]" @if ($booking->isCanceled()) disabled readonly @endif>
                                            @foreach ($additionals as $additional)
                                                <option value="{{ $additional->id }}" @if ($additional->id == old('bookingPassengerAdditionals.'. $bookingAdditional->id .'.additional_id', $bookingAdditional->additional_id)) selected @endif>{{ $additional->extendedName }} @ {{ $additional->provider->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2" style="width: 200px;" name="bookingPassengerAdditionals[{{ $bookingAdditional->id }}][booking_passenger_id]" @if ($booking->isCanceled()) disabled readonly @endif>
                                            @foreach ($booking->bookingPassengers as $bookingPassenger)
                                                <option value="{{ $bookingPassenger->id }}" @if ($bookingPassenger->id == old('bookingPassengerAdditionals.'. $bookingAdditional->id .'.booking_passenger_id', $bookingAdditional->booking_passenger_id)) selected @endif>{{ $bookingPassenger->name }}
                                            @endforeach
                                        </select>

                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    {{ $booking->currency->code }}
                                                </span>
                                            </div>
                                            <input type="text" class="form-control input-money" style="text-align: right; width: 100px;" name="bookingPassengerAdditionals[{{ $bookingAdditional->id }}][price]" value="{{ old('bookingPassengerAdditionals.'. $bookingAdditional->id .'.price', moneyDecimal($bookingAdditional->price)) }}" inputmode="numeric" @if ($booking->isCanceled()) disabled readonly @endif>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    {{ $booking->currency->code }}
                                                </span>
                                            </div>
                                            <input type="text" class="form-control input-money" style="text-align: right; width: 100px;" name="bookingPassengerAdditionals[{{ $bookingAdditional->id }}][price_net]" value="{{ moneyDecimal($bookingAdditional->price_net) }}" inputmode="numeric" @if ($booking->isCanceled()) disabled readonly @endif>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                            {{ $bookingAdditional->currencyOrigin->code }}
                                    </td>
                                    <td class="text-center align-middle">
                                            {{ $bookingAdditional->sale_coefficient }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if (!$booking->isCanceled())
                                            <a href="{{ route('backend.bookings.destroyAdditional', [$booking, $bookingAdditional]) }}" class="btn btn-danger btn-sm delete pull-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluir">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <i>{{ __('resources.adicionais_assoc') }}</i>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-rounded">
                <div class="card-header text-dark align-middle">
                    <strong>{{ __('resources.bookings.model.quotations') }}</strong>
                    <a class="btn btn-secondary btn-xs pull-right" data-toggle="collapse" href="#quotationContent" aria-expanded="true" aria-controls="quotationContent"  data-toggle="tooltip" data-placement="top" title="{{ __('messages.view') }}">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>

                <div id="quotationContent" class="card-body collapse p-0">
                    <table id="currenciesTable" class="table table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th>{{ __('resources.currencies.model.name') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.currencies.model.quotation') }}</th>
                                <th class="text-center" width="20%">{{ __('resources.currencies.model.spread') }}</th>
                                <th class="text-center" width="10%">{{ __('resources.currencies.model.spreaded_quotation') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->getQuotations() as $key => $quotation)
                                @if(is_array($quotation))
                                <tr>
                                    <td class="align-middle">{{ $quotation['name'] }}</td>
                                    <td class="text-center align-middle">{{ moneyDecimal($quotation['quotation']) }}</td>
                                    <td class="text-center align-middle">{{ moneyDecimal($quotation['spread']) }}</td>
                                    <td class="text-center align-middle">{!! spread($quotation['quotation'], $quotation['spread'], $quotation['target_currency_id'], $quotation['origin_currency_id']) !!}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="align-middle">{{ $key }}</td>
                                    <td colspan=3 class="align-middle">{{ $quotation }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
