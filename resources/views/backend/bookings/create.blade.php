@extends('backend.template.default')
@section('content')

<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.bookings.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.index') }}">
                    {{ __('resources.bookings.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.bookings.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="bookingForm" method="post" action="{{ route('backend.bookings.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.bookings.name-plural') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bookings.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bookings.model.package') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="package_id" class="form-control select2 m-b-10" style="width: 100%" onchange="handlePackageChange()">
                                    <option value disabled selected>{{ __('messages.select') }}</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" @if (!empty($selectedPackage) && $selectedPackage->id == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (!empty($selectedPackage))
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.offers.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="offer_id" class="form-control select2 m-b-10" style="width: 100%"  onchange="handleOfferChange()">
                                    <option value disabled selected>{{ __('messages.select') }}</option>
                                    @foreach ($offers as $offer)
                                        <option value="{{ $offer->id }}" @if (!empty($selectedOffer) && $selectedOffer->id == $offer->id) selected @endif>
                                            [{{ $offer->provider->name }}] {{ $offer->typeText }} @if (!empty($offer->extendedName)) {{ ' / ' . $offer->extendedName }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        @if (!empty($selectedOffer))
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>Produto da Oferta</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="product_id" class="form-control select2 m-b-10" style="width: 100%" onchange="handleOfferProductChange()">
                                    <option value disabled selected>{{ __('messages.select') }}</option>
                                    @foreach ($offerProducts as $product)
                                        @php
                                            $productId = is_object($product) ? $product->id : $product['id'];
                                            $productTitle = is_object($product) ? $product->getTitle() : $product['title'];
                                        @endphp
                                        <option value="{{ $productId }}" @if (!empty($selectedProduct) && $selectedProduct->id == $productId) selected @endif>{{ $productTitle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        @if (!empty($selectedProduct) && $mustSelectDates)
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>Periodo</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @foreach ($package->bookablePeriod as $key => $period)
                                    <div class="checkbox checkbox-success">
                                        <input name="dates[]" id="date-{{ $key }}" type="checkbox" value="{{ $period['date']->format('Y-m-d') }}" />
                                        <label for="date-{{ $key }}">{{ $period['date']->format('d/m/Y') }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if (!empty($selectedProduct))
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bookings.model.client') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="client_id" class="form-control select2 m-b-10" style="width: 100%">
                                    <option value disabled selected>{{ __('messages.select') }}</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" @if (!empty($selectedClient) && $selectedClient->id == $client->id) selected @endif>{{ mb_strtoupper($client->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('currency_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.currencies.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="currency_id">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" @if (!empty($selectedCurrency) && $selectedCurrency->id == $currency->id) selected @endif>{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    @if (!empty($selectedOffer) && !empty($selectedPackage) && !empty($selectedProduct))
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    @endif
                    <a href="{{ route('backend.bookings.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('backend.template.scripts.select-events')
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script type="text/javascript">
        function disableInputs() {
            $('select').attr('disabled', true);
        }

        function getSelectedPackageId() {
            return $('select[name="package_id"]').val();
        }

        function getSelectedOfferId() {
            return $('select[name="offer_id"]').val();
        }

        function getSelectedOfferProductId() {
            return $('select[name="product_id"]').val();
        }

        function handlePackageChange() {
            disableInputs();
            var packageId = getSelectedPackageId();
            var url = "{{ route('backend.bookings.create') }}?package_id=" + packageId;

            window.location.href = url;
        }

        function handleOfferChange() {
            disableInputs();
            var packageId = getSelectedPackageId();
            var offerId = getSelectedOfferId();
            var url = "{{ route('backend.bookings.create') }}?package_id="+ packageId +"&offer_id=" + offerId;

            window.location.href = url;
        }

        function handleOfferProductChange() {
            disableInputs();
            var packageId = getSelectedPackageId();
            var offerId = getSelectedOfferId();
            var productId = getSelectedOfferProductId();
            var url = "{{ route('backend.bookings.create') }}?package_id="+ packageId +"&offer_id=" + offerId + "&product_id=" + productId;

            window.location.href = url;
        }


    </script>
@endpush
