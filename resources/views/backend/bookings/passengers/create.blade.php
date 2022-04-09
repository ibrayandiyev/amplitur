@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.booking-passengers.name') }}</h3>
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
            <li class="breadcrumb-item active">{{ __('resources.booking-passengers.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="bookingPassengerForm" method="post" action="{{ route('backend.bookings.storePassenger', $booking) }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.booking-passengers.name') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" />
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('primary_document')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.primary_document') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="primary_document" onchange="handlePrimaryDocumentChange()">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="{{ App\Enums\DocumentType::IDENTITY }}" @if(old('primary_document') == App\Enums\DocumentType::IDENTITY) selected @endif>RG</option>
                                <option value="{{ App\Enums\DocumentType::PASSPORT }}" @if(old('primary_document') == App\Enums\DocumentType::PASSPORT) selected @endif>Passaporte</option>
                                <option value="{{ App\Enums\DocumentType::BIRTH_CERTIFICATE }}" @if(old('primary_document') == App\Enums\DocumentType::BIRTH_CERTIFICATE) selected @endif>Certidão de Nascimento</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('identity')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.identity') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="identity" value="{{ old('identity') }}">
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('uf')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.uf') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="uf">
                                <option value>{{ __('messages.select') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->iso2 }}" @if(old('uf') == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('document')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.vat_cpf') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="document" value="{{ old('document') }}">
                        </div>
                        <div class="form-group col-md-2 @if($errors->has('birthdate')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.birthdate') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" maxlength="10" class="form-control datepicker" name="birthdate" value="{{ old('birthdate') }}" placeholder="__/__/____">
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('phone')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.phone') }} <span class="text-danger">*</span></strong>
                            </label>
                            <br />
                            <input type="text" class="form-control phone-flag" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('email')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.booking-passengers.model.email') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control text-lowercase" name="email" value="{{ old('email') }}">
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

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/intl-tel-input/css/intlTelInput.css">
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
    </script>
@endpush
