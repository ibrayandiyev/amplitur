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
            <form id="bookingAdditionalForm" method="post" action="{{ route('backend.bookings.storeAdditional', $booking) }}" autocomplete="off">
                <input type="hidden" name="navigation" value="{{ old('navigation', 'products') }}" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.booking-passenger-additionals.name') }}
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
                        <div class="form-group col-md-12 @if($errors->has('additional_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.additionals.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="additional_id" style="width: 100%;">
                                @foreach ($additionals as $additional)
                                    <option value="{{ $additional->id }}" @if ($additional->id == old('additional_id')) selected @endif>{{ $additional->extendedName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 @if($errors->has('booking_passenger_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="booking_passenger_id" style="width: 100%;">
                                @foreach ($booking->bookingPassengers as $bookingPassenger)
                                    <option value="{{ $bookingPassenger->id }}" @if ($bookingPassenger->id == old('booking_passenger_id')) selected @endif>{{ $bookingPassenger->name }}
                                @endforeach
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
                                <input type="text" class="form-control input-money" name="price" value="{{ old('price', 0)}}" inputmode="numeric" style="text-align: right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
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
