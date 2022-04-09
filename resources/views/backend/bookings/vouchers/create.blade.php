@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.booking-vouchers.name') }}</h3>
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
            <li class="breadcrumb-item active">{{ __('resources.booking-vouchers.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="bookingVoucherForm" method="post" action="{{ route('backend.bookings.storeVoucher', $booking) }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.booking-vouchers.name') }}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="card border-rounded">

                            <div class="card-body">
                                <div class="form-group col-md-2 p-0">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.booking-vouchers.model.released_at') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" maxlength="10" class="form-control datepicker" name="released_at" value="{{ old('released_at', date('d/m/Y')) }}" placeholder="__/__/____" />
                                </div>
                                <div class="form-group col-md-12 p-0">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.booking-vouchers.model.services') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control summernote" name="services" rows="4">
                                            {{ '<ul>' }}
                                            @foreach ($inclusions as $inclusion)
                                                {{ '<li>' . $inclusion->getTranslation('name', $booking->bookingClient->language) . '</li>' }}
                                            @endforeach
                                            {{ '</ul>' }}
                                    </textarea>
                                </div>
                                <div class="form-group col-md-12 p-0">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.booking-vouchers.model.comments') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control summernote" name="comments" rows="4">
                                    </textarea>
                                </div>
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

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
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
    </script>
@endpush
