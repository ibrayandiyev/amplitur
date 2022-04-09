@php
    $address = $hotel->address;
    $canChange = !(user()->isProvider() && $offer->hasBookings());
    $disabled = $canChange ? null : 'disabled';
    $readonly = $canChange ? null : 'readonly';
    $navigation = old('navigation', "sales-info");
    $active_sales_info = $active_hotel_info = $active_accommodations = $active_pricing = $active_additionals = $active_gallery = "";
    switch($navigation){
        default:
        case 'sales-info':
            $active_sales_info = "active";
            break;
        case 'hotel-info':
            $active_hotel_info = "active";
            break;
        case 'accommodations':
            $active_accommodations = "active";
            break;
        case 'pricing':
            $active_pricing = "active";
            break;
        case 'additionals':
            $active_additionals = "active";
            break;
        case 'gallery':
            $active_gallery = "active";
            break;
    }
@endphp

<form id="offerForm" method="post" action="{{ route('backend.providers.companies.offers.update', [$provider, $company, $offer]) }}" autocomplete="off">
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="navigation" value="{{ old('navigation', "sales-info") }}" />
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.hotels.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$active_sales_info}}" data-toggle="tab" href="#sales-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bus-trip.sales-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_hotel_info}}" data-toggle="tab" href="#hotel-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.hotels.info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_accommodations}}" data-toggle="tab" href="#accommodations" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.hotel-accommodations.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_pricing}}" data-toggle="tab" href="#pricing" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.hotels.pricing') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{$active_additionals}}" data-toggle="tab" href="#additionals" role="tab">
                        <a class="nav-link" data-toggle="tab" href="#additionals" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.additionals.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_gallery}}" data-toggle="tab" href="#gallery" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.gallery.name') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane {{$active_sales_info}}" id="sales-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('package_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.packages.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="package_id" class="form-control " {{$disabled}}>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id}}" @if (old('package_id', $offer->package_id) == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('expires_at')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.expires_at') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control datetimepicker" name="expires_at" value="{{ old('expires_at', $offer->expiresAtLocal) }}" placeholder="__/__/____, 00:00" >
                            </div>
                            <div class="form-group col-md-3  @if($errors->has('currency')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.sales-currency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if($disabled)
                                <input type='hidden' name="currency" value='{{ $offer->currency }}' >
                                <select class="form-control"  disabled>
                                @else
                                <select class="form-control" name="currency" {{$disabled}}>
                                @endif
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                                    <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                                    <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                                    <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3  @if($errors->has('sale_coefficient_id')) has-danger @endif" @if(!user()->canManageOfferSaleCoefficient($offer)) style="display: none;" @endif>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.offers.model.coefficient') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>

                                @if (user()->canManageOfferSaleCoefficient($offer))
                                    <select class="form-control " name="sale_coefficient_id">
                                        <option value>{{ __('messages.select') }}</option>
                                        @foreach ($saleCoefficients as $saleCoefficient)
                                            <option value="{{ $saleCoefficient->id }}" data-coefficient="{{ $saleCoefficient->value }}" @if (old('sale_coefficient_id', $offer->sale_coefficient_id) == $saleCoefficient->id) selected @endif>
                                                {{ App\Models\SaleCoefficient::find($saleCoefficient->id)->extendedName }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control " name="sale_coefficient_id" style="display: none">
                                        <option value="{{ $offer->saleCoefficient->id }}" data-coefficient="{{ $offer->saleCoefficient->value }}" selected>{{ App\Models\SaleCoefficient::find($offer->saleCoefficient->id)->extendedName }}</option>
                                    </select>
                                @endif

                            </div>
                            <div class="form-group col-md-3 @if($errors->has('minimum_stay')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.minimum_stay') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="2" class="form-control" name="hotel_offer[minimum_stay]" value="{{ old('minimum_stay', $offer->hotelOffer->minimum_stay) }}" {{$readonly}}>
                            </div>
                            @if (auth()->user()->isMaster())
                                <div class="form-group col-md-3  @if($errors->has('can_register_additionals')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.offers.model.can_register_additionals') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="can_register_additionals">
                                        <option value="0" @if (old('can_register_additionals', $offer->can_register_additionals) == 0) selected @endif>{{ __('messages.no') }}</option>
                                        <option value="1" @if (old('can_register_additionals', $offer->can_register_additionals) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 @if($errors->has('status')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.offers.model.status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="status">
                                        <option value="{{ \App\Enums\ProcessStatus::IN_ANALYSIS }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::IN_ANALYSIS) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::ACTIVE }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::ACTIVE) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::REFUSED }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::REFUSED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::REFUSED) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::SUSPENDED }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::SUSPENDED) }}</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane {{$active_hotel_info}}" id="hotel-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('hotel.name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type='hidden' name="hotel_offer[hotel_id]" class="form-control " value="{{ old('hotel_offer.hotel_id', $hotel->id) }}"   />
                                <input name="hotel[name]" class="form-control " value="{{ old('hotel.name', $hotel->name) }}" {{$readonly}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.country') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[country]" {{ $disabled }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if (isset($address->country) && $address->country == $country->iso2) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($canChange)
                                    <select class="form-control" name="address[state]" {{ $readonly }}></select>
                                @else
                                    <input type="text" class="form-control" name="address[state]" value="{{ state($address->country, $address->state) }}" {{$readonly}}>
                                @endif
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if ($canChange)
                                    <select class="form-control" name="address[city]" {{ $readonly }}></select>
                                @else
                                    <input type="text" class="form-control" name="address[city]" value="{{ city($address->city) }}"  {{ $readonly }} />
                                @endif
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', ($address->zip)??'') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', ($address->address)??'') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', ($address->number)??'') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', ($address->neighborhood)??'') }}" {{ $readonly }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', ($address->complement)??'') }}" {{ $readonly }}>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('hotel.category_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.category') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control " name="hotel[category_id]"{{$readonly}} >
                                    @foreach ($hotelCategories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == old('hotel.category_id', $hotel->category_id)) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('hotel.checkin')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkin') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkin]" type="time" class="form-control" value="{{ old('hotel.checkin', $hotel->checkin) }}" {{$readonly}}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('hotel.checkout')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.checkout') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input name="hotel[checkout]" type="time" class="form-control" value="{{ old('hotel.checkout', $hotel->checkout) }}" {{$readonly}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('hotel.inclusions')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.hotels.model.inclusions') }}</strong>
                                </label>
                                <select id='selectionHS' name="hotel[hotel_structures][]" class="select2 m-b-10 select2-multiple copyPasteHotelStructures" style="width: 100%" multiple="multiple" {{$disabled}}>
                                    @foreach ($hotelStructures as $hotelStructure)
                                        <option value="{{ $hotelStructure->id }}" @if ($hotel->structures->contains('id', $hotelStructure->id)) selected @endif>{{ $hotelStructure->name }}</option>
                                    @endforeach
                                </select>
                                @include("backend.tools.copypastebutton", ["targetObject" => "copyPasteHotelStructures"])
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('observations')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.longtrip-routes.model.observations') }}</strong>
                                </label>
                                <select id="selection1" name="hotel_offer[observations][]" class="select2 m-b-10 select2-multiple copyPasteObservation" style="width: 100%" multiple="multiple" {{$disabled}}>
                                    @foreach ($observations as $observation)
                                        <option value="{{ $observation->id }}" @if($hotelOffer && $hotelOffer->observations->contains('id', $observation->id)) selected @endif>{{ $observation->name }}</option>
                                    @endforeach
                                </select>
                                @include("backend.tools.copypastebutton", ["targetObject" => "copyPasteObservation"])
                            </div>
                        </div>

                        @if (user()->canManageOfferExtras($offer))
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.longtrip-routes.model.extra_observations') }}</strong>
                                    </label>
                                    <div class="tab-content br-n pn">
                                        <div id="basic-info-extra_observations-pt-br" class="tab-pane active">
                                            <textarea class="form-control summernote" name="hotel[extra_observations][pt-br]" placeholder="pt-br">{!! $hotel->getTranslation('extra_observations', 'pt-br', false) !!}</textarea>
                                        </div>
                                        <div id="basic-info-extra_observations-en" class="tab-pane">
                                            <textarea class="form-control summernote" name="hotel[extra_observations][en]" placeholder="en">{!! $hotel->getTranslation('extra_observations', 'en', false) !!}</textarea>
                                        </div>
                                        <div id="basic-info-extra_observations-es" class="tab-pane">
                                            <textarea class="form-control summernote" name="hotel[extra_observations][es]" placeholder="es">{!! $hotel->getTranslation('extra_observations', 'es', false) !!}</textarea>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills m-b-30">
                                        <li class="nav-item"><a href="#basic-info-extra_observations-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                        <li class="nav-item"><a href="#basic-info-extra_observations-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                        <li class="nav-item"><a href="#basic-info-extra_observations-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12 map-container" id="map"></div>
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude', ($address->latitude??'')) }}" {{$disabled}}  />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude', ($address->longitude)??'') }}" {{$disabled}} />
                        </div>
                    </div>

                    <div class="tab-pane {{$active_accommodations}}" id="accommodations" role="tab-panel">
                        @include('backend.offers.types.hotel.accommodations.table')
                        @if ($canChange)
                            <a href="{{ route('backend.providers.companies.offers.hotel.createHotelAccommodation', [$provider, $company, $offer]) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i>
                                {{ __('resources.hotel-accommodations.create') }}
                            </a>
                        @endif
                    </div>

                    <div class="tab-pane {{$active_pricing}}" id="pricing" role="tab-panel">
                        @include('backend.offers.types.hotel.pricing')
                    </div>

                    <div class="tab-pane {{$active_additionals}}" id="additionals" role="tab-panel">
                        @include('backend.offers.types.hotel.additionals')
                    </div>

                    <div class="tab-pane {{$active_gallery}}" id="gallery" role="tab-panel">
                        <div class="row mb-20">
                            <div class="col-md-12">
                                <a href="{{ route('backend.providers.companies.offers.gallery.create', [$provider, $company, $offer]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus"></i>
                                    {{ __('resources.images.create') }}
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($offer->images as $image)
                            <div class="col-md-3">
                                <div class="card gallery-image-card">
                                    <a href="{{ route('backend.providers.companies.offers.gallery.edit', [$provider, $company, $offer, $image]) }}">
                                        <div class="label-default-image">
                                            {!! $image->getIsDefaultLabel() !!}
                                        </div>
                                        <img class="card-img-top img-responsive" src="{{ $image->getUrl() }}" alt="{{ $image->title }}">
                                    </a>
                                    <div class="card-body">
                                        <p class="card-text text-center">{{ $image->title }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('backend.providers.companies.offers.gallery.edit', [$provider, $company, $offer, $image]) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('backend.providers.companies.offers.gallery.destroy', [$provider, $company, $offer, $image]) }}" class="text-danger btn-sm delete pull-right">
                                            <i class="fa fa-trash"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @if (user()->canUpdateOffer($offer))
                <button type="submit" class="btn btn-primary save">
                    <i class="fa fa-save"></i> {{ __('messages.save') }}
                </button>
            @endif

            <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>

            @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.destroy', [$provider, $company, $offer]) }}"  class="btn btn-danger delete pull-right">
                    <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                </a>
            @endif
        </div>
    </div>
</form>

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
@endpush

@push('scripts')
    <script src="/backend/js/resources/pricing.hotel.js"></script>
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $('.selectpicker').selectpicker();

        $('.summernote').summernote({
            height: 150,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    </script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            mask: true
        });
    </script>
    @if ($canChange)
        <script type="text/javascript">
            fillAddress({
                country: "{{ old('address.country', ($address->country)??'') }}",
                state: "{{ old('address.state', ($address->state)??'') }}",
                city: "{{ old('address.city', ($address->city)??'') }}"
            });
        </script>
    @endif
@endpush
