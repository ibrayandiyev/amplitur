@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.offers.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a> :
                <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }}</a> :
                <a href="{{ route('backend.providers.companies.edit', [$provider, $company]) }}">{{ $company->company_name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}">{{ __('resources.offers.name-plural') }}</a>
                : {{ __('resources.offers.create') }}
            </li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.providers.companies.offers.prepare', [$provider, $company]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.offers.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.offers.info') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            {{ __('resources.offers.hints.select-event-type') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('messages.offer.offer_event') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control select2" name="event_id">
                            @csrf
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('messages.offer.offer_type') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="type">
                            <option value="">{{ __('messages.select') }}</option>
                            <optgroup label="{{ __('resources.service_principal') }}">
                                <option value="{{ \App\Enums\OfferType::BUSTRIP }}">{{ __('resources.offers.model.types.bus-trip') }}</option>
                                <option value="{{ \App\Enums\OfferType::SHUTTLE }}">{{ __('resources.offers.model.types.shuttle') }}</option>
                                <option value="{{ \App\Enums\OfferType::LONGTRIP }}">{{ __('resources.offers.model.types.longtrip') }}</option>
                                <option value="{{ \App\Enums\OfferType::HOTEL }}">{{ __('resources.offers.model.types.hotel') }}</option>
                            </optgroup>

                            <optgroup label="{{ __('resources.service_adicional') }}">
                                <option value="{{ \App\Enums\OfferType::TICKET }}">{{ __('resources.offers.model.types.ticket') }}</option>
                                <option value="{{ \App\Enums\OfferType::FOOD }}">{{ __('resources.offers.model.types.food') }}</option>
                                <option value="{{ \App\Enums\OfferType::TRAVEL_INSURANCE }}">{{ __('resources.offers.model.types.travel-insurance') }}</option>
                                <option value="{{ \App\Enums\OfferType::AIRFARE }}">{{ __('resources.offers.model.types.airfare') }}</option>
                                <option value="{{ \App\Enums\OfferType::TRANSFER }}">{{ __('resources.offers.model.types.transfer') }}</option>
                                <option value="{{ \App\Enums\OfferType::ADDITIONAL }}">{{ __('resources.offers.model.types.additional') }}</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group col-md-12" style="display: none;" data-package-selection>
                        <label class="form-control-label">
                            <strong> {!! __('messages.offer.evento_exist') !!}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="package_id">
                            <option value="">{{ __('messages.select') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="btn btn-primary" id="nextStep">
                    <i class="fa fa-arrow-right"></i> {{ __('messages.continue') }}
                </a>
                <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}" class="btn btn-secondary">
                    <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        let eventSelect = $('[name="event_id"]');
        let packageSelect = $('[name="package_id"]');
        let typeSelect = $('[name="type"]');
        let nextStepButton = $('#nextStep');
        let route = "{{ route('backend.providers.companies.offers.create', [$provider, $company]) }}";
        let divPackageSelection = $('[data-package-selection]');
        let preloader = $('.preloader');

        eventSelect.change(function () {
            let eventId = eventSelect.val();
            preloader.show();
            divPackageSelection.hide();

            packageSelect.find('option').remove();
            packageSelect.append('<option value="">{{ __('messages.select') }}</option>');

            $.ajax({
                url: '/api/events/' + eventId + '/packages',
            }).done((response) => {

                if (response.length == 0) {
                    return;
                }

                response.map((package) => {
                    packageSelect.append('<option value="' + package.id + '">' + package.extendedName + '</option>');
                });

                divPackageSelection.show();
            }).always(() => {
                preloader.hide();
            });

            changeCreateUrl(eventId, typeSelect.val());
        });

        typeSelect.change(function () {
            changeCreateUrl(eventSelect.val(), typeSelect.val());
        });

        packageSelect.change(function () {
            changeCreateUrl(eventSelect.val(), typeSelect.val(), packageSelect.find('option:selected').val());
        });

        function changeCreateUrl(event, type, package) {
            let url = `${route}?event_id=${event}&type=${type}`;

            if (package != undefined) {
                url += `&package_id=${package}`;
            }

            nextStepButton.attr('href', url);

            if (event && type) {
                nextStepButton.show();
            } else {
                nextStepButton.hide();
            }
        }

        nextStepButton.hide();
    });
</script>
@include('backend.template.scripts.select-events')
@endpush
