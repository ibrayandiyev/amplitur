@extends('backend.template.default')
@section('content')

@php
    if($navigation == null){
        $navigation = "basic-info";
    }
    $activeBi = $activeProducts = $activePassengers = $activePayment = $activeVouchers =
    $activeLogs = "";
    switch($navigation){
        default:
        case "basic-info":
            $activeBi = "active";
            break;
        case "products":
            $activeProducts = "active";
            break;
        case "passengers":
            $activePassengers = "active";
            break;
        case "payment":
            $activePayment = "active";
            break;
        case "vouchers":
            $activeVouchers = "active";
            break;
        case "logs":
            $activeLogs = "active";
            break;
    }
@endphp

@if ($booking->isCanceled())
<div class="alert alert-warning">
    <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning')  }}</h4>
    {{ __('resources.bookings.is-canceled') }}
</div>
@endif
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.bookings.name-plural') }} - {{$booking->id}}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.bookings.index') }}">
                    {{ __('resources.bookings.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.bookings.edit') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">

            @if (user()->canSeeBookingContract())
            <a href="{{ route('backend.bookings.contract', $booking) }}" class="btn btn-sm btn-info" target="_blank">
                <i class="mdi mdi-file-pdf"> </i>{{ __('resources.bookings.contract') }}
            </a>
            @endif
            @if (user()->canSeeBookingConfirmation())
                <a href="{{ route('backend.bookings.confirmation', $booking) }}" class="btn btn-sm btn-secondary" target="_blank">
                    <i class="fa fa-check"></i>
                    {{ __('resources.bookings.internal-confirmation') }}
                </a>
            @endif
            @if ($booking->canBeCanceled() && user()->canCancelBooking())
                <a href="{{ route('backend.bookings.cancel', $booking) }}" class="btn btn-sm btn-danger warn" data-toggle="tooltip" data-placement="top" title="{{ __('resources.bookings.hints.cancel-and-return-to-stock') }}">
                    <i class="fa fa-ban"></i>
                    {{ __('resources.bookings.to-cancel') }}
                </a>
            @endif

            @if (user()->canRefundBookingPayment())
                <a href="#" class="btn btn-sm btn-primary">
                    <i class="fa fa-undo"></i>
                    {{ __('resources.bookings.to-refund') }}
                </a>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="bookingForm" method="post" action="{{ route('backend.bookings.update', $booking) }}" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="_method" value="put" />
                <input type="hidden" name="navigation" value="{{ old('navigation', $navigation) }}" />

                @csrf
                <div class="labelx label-service">
                   {{ $booking->getProductTypeName()}} - {{ $booking->package->extendedNameDate }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    @if (user()->canManageBookingDetails())
                        <li class="nav-item">
                            <a class="nav-link {{ $activeBi }}" data-toggle="tab" href="#basic-info" role="tab">
                                <span class="hidden-sm-up"><i class="ti-user"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.bookings.basic-info') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (user()->canManageBookingDetails())
                        <li class="nav-item">
                            <a class="nav-link {{ $activeProducts }}" data-toggle="tab" href="#products" role="tab">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.bookings.model.products') }}</span>
                            </a>
                        </li>
                    @endif
                        <li class="nav-item">
                            <a class="nav-link  {{ $activePassengers }} @if (!user()->canManageBookingDetails()) active @endif" data-toggle="tab" href="#passengers" role="tab">
                                <span class="hidden-sm-up"><i class="ti-agenda"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.bookings.model.passengers') }}</span>
                            </a>
                        </li>
                    @if (user()->canManageBookingDetails())
                        <li class="nav-item">
                            <a class="nav-link {{ $activePayment }}" data-toggle="tab" href="#payment" role="tab">
                                <span class="hidden-sm-up"><i class="ti-lock"></i></span>
                                <span class="hidden-xs-down">{{ __('resources.bookings.payment') }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ $activeVouchers }}" data-toggle="tab" href="#vouchers" role="tab">
                            <span class="hidden-sm-up"><i class="ti-ticket"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bookings.vouchers') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeLogs }}" data-toggle="tab" href="#logs" role="tab">
                            <span class="hidden-sm-up"><i class="ti-calendar"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.logs.logs') }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-4">
                    @if (user()->canManageBookingDetails())
                        <div class="tab-pane {{ $activeBi }}" id="basic-info" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.package') }}</strong>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value=" {{ $booking->package->extendedNameDate }}" readonly>
                                        <div class="input-group-append">
                                            <a class="btn btn-info" href="{{ route('backend.packages.edit', $booking->package->id) }}" target="_blank" data-toggle="tooltip" data-placement="top" title="{{ __('messages.view') }}">
                                                <i class="fa fa-eye text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.client') }}</strong>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value=" {{ $booking->client->name }}" readonly>
                                        <div class="input-group-append">
                                            <a class="btn btn-info" href="{{ route('backend.clients.edit', $booking->client->id) }}" target="_blank" data-toggle="tooltip" data-placement="top" title="{{ __('messages.view') }}">
                                                <i class="fa fa-eye text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <label class="form-control-label text-center">
                                        <strong>{{ __("resources.label.logar") }}</strong>
                                    </label>
                                        <div class="skip">
                                            <a class="btn btn-warning" href="{{ route('backend.clients.loginAsCustomer', $booking->client->id) }}" target='_blank' data-toggle="tooltip" data-placement="top" title="{{ __('messages.login_as_customer') }}">
                                                <i class="mdi mdi-account-key text-white"></i>
                                            </a>
                                        </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.ip') }}</strong>
                                    </label>
                                    <input class="form-control" value="{{ $booking->ip }}" readonly />
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.currency') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" value="{{ $booking->currency->name }} ({{ $booking->currency->code }})" readonly />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.created_at') }}</strong>
                                    </label>
                                    <input type="text" maxlength="10" class="form-control" value="{{ $booking->createdAtLocal }}" readonly />
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.starts_at') }}</strong>
                                    </label>
                                    <input type="text" maxlength="10" class="form-control " value="{{ $booking->startsAtLabel }}" readonly />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.expired_at') }}</strong>
                                    </label>
                                    <input type="text" maxlength="10" name="booking[expired_at]" class="form-control datetimepicker" value="{{ $booking->expiredAtLocal }}" @if ($booking->isCanceled()) disabled readonly @endif />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="booking[status]" @if ($booking->isCanceled() && !user()->canManageBookingDetails()) disabled readonly @endif>
                                        <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('status', $booking->status) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if (old('status', $booking->status) == \App\Enums\ProcessStatus::CONFIRMED) selected @endif>{{ __('resources.process-statues.confirmed') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if (old('status', $booking->status) == \App\Enums\ProcessStatus::CANCELED) selected @endif>{{ __('resources.process-statues.canceled') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::BLOCKED }}" @if (old('status', $booking->status) == \App\Enums\ProcessStatus::BLOCKED) selected @endif>{{ __('resources.process-statues.blocked') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.document_status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="booking[document_status]" @if ($booking->isCanceled()) disabled readonly @endif>
                                        <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('document_status', $booking->document_status) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if (old('document_status', $booking->document_status) == \App\Enums\ProcessStatus::CONFIRMED) selected @endif>{{ __('resources.process-statues.confirmed') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::PARTIAL_RECEIVED }}" @if (old('document_status', $booking->document_status) == \App\Enums\ProcessStatus::PARTIAL_RECEIVED) selected @endif>{{ __('resources.process-statues.partial_received') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.payment_status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="booking[payment_status]">
                                        <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('payment_status', $booking->payment_status) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if (old('payment_status', $booking->payment_status) == \App\Enums\ProcessStatus::CONFIRMED) selected @endif>{{ __('resources.process-statues.confirmed') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::PARTIAL_PAID }}" @if (old('payment_status', $booking->payment_status) == \App\Enums\ProcessStatus::PARTIAL_PAID) selected @endif>{{ __('resources.process-statues.partial_paid') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::OVERDUED }}" @if (old('payment_status', $booking->payment_status) == \App\Enums\ProcessStatus::OVERDUED) selected @endif>{{ __('resources.process-statues.overdued') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::REFUNDED }}" @if (old('payment_status', $booking->payment_status) == \App\Enums\ProcessStatus::REFUNDED) selected @endif>{{ __('resources.process-statues.refunded') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.voucher_status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="booking[voucher_status]" @if ($booking->isCanceled()) disabled readonly @endif>
                                        <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if (old('voucher_status', $booking->voucher_status) == \App\Enums\ProcessStatus::PENDING) selected @endif>{{ __('resources.process-statues.pending') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::RELEASED }}" @if (old('voucher_status', $booking->voucher_status) == \App\Enums\ProcessStatus::RELEASED) selected @endif>{{ __('resources.process-statues.released') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.bookings.model.comments') }}</strong>
                                    </label>
                                    @if (!empty($booking->comments))
                                        <p>{{ $booking->comments }}</p>
                                    @else
                                        <p>
                                            <i>{{ __('resources.bookings.no_booking_info') }}</i>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @php ($client = $booking->bookingClient)
                            @include('backend.bookings.partials.customer')

                        </div>
                    @endif

                    @if (user()->canManageBookingDetails())
                        @include('backend.bookings.partials.products')
                    @endif

                    <div class="tab-pane {{ $activePassengers }} @if (!user()->canManageBookingDetails()) active @endif" id="passengers" role="tabpanel">
                        <div class="row">
                            @if (user()->canManageBookingDetails())
                                <div class="col-md-12 mb-20">
                                    <a href="{{ route('backend.bookings.createPassenger', $booking) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                        {{ __('messages.add-item') }}
                                    </a>
                                </div>
                            @endif

                            @foreach ($booking->bookingPassengers as $key => $bookingPassenger)
                                <div class="col-md-12 passenger">
                                    <div class="card border-rounded">
                                        <div class="card-header text-dark align-middle">
                                            @php ($i = $key + 1)
                                            <strong>{{ __('resources.booking-passengers.name') }} #{{ $i }}</strong>

                                            @if (user()->canManageBookingDetails() && !$booking->isCanceled())
                                                <a href="{{ route('backend.bookings.destroyPassenger', [$booking, $bookingPassenger]) }}" class="btn btn-danger btn-xs delete pull-right" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-12 @if($errors->has('bookingPassengers.'.$key.'.name')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.name') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="bookingPassengers[{{ $bookingPassenger->id }}][name]" value="{{ old('bookingPassengers.'.$key.'.name', $bookingPassenger->name) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif />
                                                </div>
                                                <div class="form-group col-md-3 @if($errors->has('bookingPassengers.'.$key.'.primary_document')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.primary_document') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control bp_{{$key}}_pd" name="bookingPassengers[{{ $bookingPassenger->id }}][primary_document]" onchange="handlePrimaryDocumentChange('bp_{{$key}}')" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                        <option value>{{ __('messages.select') }}</option>
                                                        <option value="{{ App\Enums\DocumentType::IDENTITY }}" data-field="bp_{{$key}}_identity" @if(old('bookingPassengers.'.$key.'.primary_document', $bookingPassenger->primary_document) == App\Enums\DocumentType::IDENTITY) selected @endif>RG</option>
                                                        <option value="{{ App\Enums\DocumentType::PASSPORT }}" data-field="bp_{{$key}}_passport" @if(old('bookingPassengers.'.$key.'.primary_document', $bookingPassenger->primary_document) == App\Enums\DocumentType::PASSPORT) selected @endif>Passaporte</option>
                                                        <option value="{{ App\Enums\DocumentType::BIRTH_CERTIFICATE }}" data-field="bp_{{$key}}_bith_date" @if(old('bookingPassengers.'.$key.'.primary_document', $bookingPassenger->primary_document) == App\Enums\DocumentType::BIRTH_CERTIFICATE) selected @endif>Certidão de Nascimento</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 bp_{{$key}}_primary_document bp_{{$key}}_identity @if($errors->has('bookingPassengers.'.$key.'.identity')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.identity') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="bookingPassengers[{{ $bookingPassenger->id }}][identity]" value="{{ old('bookingPassengers.'.$key.'.identity', $bookingPassenger->identity) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                                <div class="form-group col-md-2 bp_{{$key}}_primary_document bp_{{$key}}_passport @if($errors->has('bookingPassengers.'.$key.'.identity')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.passport') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control " name="bookingPassengers[{{ $bookingPassenger->id }}][passport]" value="{{ old('bookingPassengers.'.$key.'.passport', $bookingPassenger->passport) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                                <div class="form-group col-md-2 @if($errors->has('bookingPassengers.'.$key.'.uf')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.uf') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control" name="bookingPassengers[{{ $bookingPassenger->id }}][uf]" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                        <option value>{{ __('messages.select') }}</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->iso2 }}" @if(old('bookingPassengers.'.$key.'.uf', $bookingPassenger->uf) == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3 @if($errors->has('bookingPassengers.'.$key.'.document')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.vat_cpf') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control document-input" name="bookingPassengers[{{ $bookingPassenger->id }}][document]" value="{{ old('bookingPassengers.'.$key.'.passport', $bookingPassenger->document) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                                <div class="form-group col-md-2 @if($errors->has('bookingPassengers.'.$key.'.birthdate')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.birthdate') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" maxlength="10" class="form-control datepicker birthdate-input" name="bookingPassengers[{{ $bookingPassenger->id }}][birthdate]" value="{{ old('booking.bookingPassengers.'.$key.'.birthdate', $bookingPassenger->birthdate ? $bookingPassenger->birthdate->format('d/m/Y') : '') }}" placeholder="__/__/____" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                                <div class="form-group col-md-3 @if($errors->has('bookingPassengers.'.$key.'.phone')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.phone') }} <span class="text-danger">*</span></strong>
                                                    </label>
                                                    <br />
                                                    <input type="text" class="form-control phone-flag phone-input" name="bookingPassengers[{{ $bookingPassenger->id }}][phone]" value="{{ old('bookingPassengers.'.$key.'.phone', $bookingPassenger->phone) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                                <div class="form-group col-md-3 @if($errors->has('bookingPassengers.'.$key.'.email')) has-danger @endif">
                                                    <label class="form-control-label">
                                                        <strong>{{ __('resources.booking-passengers.model.email') }}</strong>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" class="form-control text-lowercase" name="bookingPassengers[{{ $bookingPassenger->id }}][email]" value="{{ old('bookingPassengers.'.$key.'.email', $bookingPassenger->email) }}" @if (!user()->canManageBookingDetails()) readonly @endif @if ($booking->isCanceled()) disabled readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @include("backend.bookings.partials.booking_bills")

                    <div class="tab-pane {{ $activeVouchers }}" id="vouchers" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 mb-20">
                                <a href="{{ route('backend.bookings.createVoucher', $booking) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                    {{ __('messages.add-item') }}
                                </a>
                            </div>
                            @foreach($booking->bookingVouchers as $i => $bookingVoucher)
                                @php($key = $i + 1)
                                <div class="col-md-12 accordion">
                                    <div class="card border-rounded" >
                                        <div class="card-header text-dark" >
                                            <strong data-toggle="collapse" data-target="#collapse{{ $loop->iteration}}" aria-expanded="true" aria-controls="collapse{{ $loop->iteration}}" style="cursor:pointer;">{{ __('resources.booking-vouchers.name') }} #{{ $key }}</strong>
                                            <a href="{{ route('backend.bookings.destroyVoucher', [$booking, $bookingVoucher]) }}" class="btn btn-danger delete btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a href="{{ route('backend.bookings.viewVoucher', [$booking, $bookingVoucher]) }}" class="btn btn-secondary btn-xs mr-10 pull-right" data-toggle="tooltip" data-placement="top" title="{{ __('messages.view') }}" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div id="collapse{{ $loop->iteration}}" class="card-body collapse @if($loop->iteration == 1) show @endif">
                                            <div class="form-group col-md-2 p-0">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.booking-vouchers.model.released_at') }}</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" maxlength="10" class="form-control datepicker" name="bookingVoucher[{{$bookingVoucher->id}}][released_at]' }}" value="{{ old('bookingVoucher.'. $bookingVoucher->id .'.released_at', $bookingVoucher->releasedAtLabel) }}" placeholder="__/__/____" @if ($booking->isCanceled()) disabled readonly @endif />
                                            </div>
                                            <div class="form-group col-md-12 p-0">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.booking-vouchers.model.services') }}</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control summernote" name="bookingVoucher[{{$bookingVoucher->id}}][services]" rows="4" @if ($booking->isCanceled()) disabled readonly @endif>{!! old('bookingVoucher.'. $bookingVoucher->id .'.services', $bookingVoucher->services) !!}</textarea>
                                            </div>
                                            <div class="form-group col-md-12 p-0">
                                                <label class="form-control-label">
                                                    <strong>{{ __('resources.booking-vouchers.model.comments') }}</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control summernote" name="bookingVoucher[{{$bookingVoucher->id}}][comments]" rows="4" @if ($booking->isCanceled()) disabled readonly @endif>{!! old('bookingVoucher.'. $bookingVoucher->id .'.comments', $bookingVoucher->comments) !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="booking_voucher_files">
                                    <strong>{{ __('messages.files') }}</strong>
                                </label>
                                <div class="row">
                                    <div class="col-md-12 mb-20">
                                        @foreach ($booking->bookingVoucherFiles as $bookingVoucherFile)
                                            <a href="{{ $bookingVoucherFile->getVoucherUrl() }}" target="_blank" class="btn btn-secondary waves-effect waves-light">
                                                <span class="btn-label">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                                {{ $bookingVoucherFile->title }}
                                            </a>
                                            <a href="{{ route('backend.bookings.destroyVoucherFile', [$booking, $bookingVoucherFile]) }}" class="btn btn-sm text-danger delete mr-10">
                                                <span class="btn-label">
                                                    <i class="fa fa-trash"></i>
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="file" class="form-control" name="booking_voucher_files[]" id="booking_voucher_files" multiple @if ($booking->isCanceled()) disabled readonly @endif />
                                <div id="booking_voucher_selected_files" class="mt-10"></div>
                            </div>
                        </div>
                    </div>

                    @include("backend.bookings.logs.logs")
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.bookings.index') }}" class="btn btn-secondary">
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
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
    @include('backend.template.scripts.select-events')
    @include('backend.template.scripts.delete-logs', ['deleteLogRouteUrl' => route("backend.bookings.deleteLog", $booking)])
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script src="/backend/js/resources/booking.js"></script>
    <script src="/backend/js/app.js"></script>
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            mask: true
        });
        $(".passenger").each(function(i, elem){
            handlePrimaryDocumentChange('bp_'+i);
        });
    </script>
    <script>
        $('.summernote').summernote({
            height: 150,
            minHeight: null,
            maxHeight: null,
            focus: false
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });

        $('#submitLogButton').click(function (e)  {
            e.preventDefault();

            let message = $('input[name="log[message]"]').val();
            let level = $('select[name="log[level]"]').val();
            let csrf = $('input[name="_token"]').val();
            let url = '{{ route("backend.bookings.storeLog", $booking) }}';

            let form = $('<form>', {
                'action': url,
                'method': 'POST',
            })
            .append(`<input type="hidden" name="log[message]" value="${message}" />`)
            .append(`<input type="hidden" name="log[level]" value="${level}" />`)
            .append(`<input type="hidden" name="_token" value="${csrf}" />`)
            .appendTo(document.body);

            form.submit();
        });

        $('#booking_voucher_files').change(function() {
            let names = [];
            let selectedFilesField = $('#booking_voucher_selected_files');

            selectedFilesField.empty();

            for (let i = 0; i < $(this).get(0).files.length; ++i) {
                let name = $(this).get(0).files[i].name;
                names.push(name);
                selectedFilesField.append(`<span class="label label-light-inverse mr-10">${name}</span>`)
            }
        });
    </script>

    @if (user()->canManageBookingDetails())
        <script type="text/javascript">
            $(document).ready(function () {
                fillAddress({
                    country: "{{ old('address.country', $client->address_country) }}",
                    state: "{{ old('address.state', $client->address_state) }}",
                    city: "{{ old('address.city', $client->address_city) }}"
                }, true);
            });
        </script>
    @endif
@endpush
