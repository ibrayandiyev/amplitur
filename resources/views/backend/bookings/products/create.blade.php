@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.booking-passenger-additionals.name') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.index') }}">
                    {{ __('resources.bookings.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.edit', $booking) }}">
                    {{ $booking->id }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.booking-passenger-additionals.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            @php 
            ($packageProducts = $booking->package->getProducts(App\Enums\OfferType::CLASS_TYPE_OFFER_TYPE[$booking->product_type], null, $companyId));
            ($packageBookablePeriod = $booking->package->bookablePeriod);
            @endphp
            <form id="bookingAdditionalForm" method="post" action="{{ route('backend.bookings.storeProduct', $booking) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="navigation" value="{{ old('navigation', 'products') }}" />
                <div class="labelx label-service">
                    {{ __('resources.booking-products.name') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('product_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-products.provider') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="company_id" style="width: 100%" onchange="handleCompanyChange()" @if ($booking->isCanceled()) disabled readonly @endif>
                                @foreach($companies as $company)
                                    <option
                                        @if($companyId == $company->id) selected @endif
                                        value="{{ $company->id }}">
                                        {{ $company->provider->name }} - {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 @if($errors->has('product_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.bookings.model.offer') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}" />
                            <select class="form-control select2 selectService" name="product_id" data-objectClass="bp-1"  style="width: 100%" @if ($booking->isCanceled()) disabled readonly @endif>
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
                                        >
                                        {{ $product->getExtendedTitle() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(count($packageProducts) >0)
                        <div class="form-group col-md-12 @if($errors->has('date')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-products.model.date') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control bp-1" name="date" @if ($booking->isCanceled()) disabled readonly @endif>
                                
                            </select>
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('price')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passenger-additionals.model.price') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{ $booking->currency->code }}
                                    </span>
                                </div>
                                <input type="text" class="form-control input-money" name="price" value="{{ old('price', 0)}}" inputmode="numeric" style="text-align: right;" />
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    @if(count($packageProducts) >0)
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    @endif
                    <a href="{{ route('backend.bookings.edit', $booking) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="/backend/js/resources/booking.js"></script>
    <script type="text/javascript">
        function getSelectedProviderId() {
            return $('select[name="provider_id"]').val();
        }

        function getSelectedCompanyId() {
            return $('select[name="company_id"]').val();
        }

        function handleProviderChange() {
            var providerId = getSelectedProviderId();
            var url = "{{ route('backend.bookings.createProduct', $booking)  }}?provider_id="+ providerId ;

            window.location.href = url;
        }

        function handleCompanyChange() {
            var companyId = getSelectedCompanyId();
            var url = "{{ route('backend.bookings.createProduct', $booking)  }}?company_id="+ companyId ;

            window.location.href = url;
        }

    </script>
@endpush
