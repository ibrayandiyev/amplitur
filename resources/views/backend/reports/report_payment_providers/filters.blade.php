@php
    $master = user()->isMaster();
@endphp
<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>{{ __('messages.filters') }}</th>
        </tr>
    </thead>
    <tr>
        <td>
            <form method="GET" action="{{ route('backend.reports.report_payment_providers.index') }}" autocomplete="off">
                <div class="tab-pane active" id="basic-info" role="">
                    <div class="card-body">
                        <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="form-control-label">
                                        <strong>{{__('report.package')}}</strong>
                                    </label>
                                    <select name="package_id[]" class="form-control select2 m-b-10" style="width: 100%">
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}"
                                            @if(isset($_filter_params['package_id']) && in_array($package->id, $_filter_params['package_id']) ) selected @endif
                                            >{{ $package->extendedName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($master)
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{__('report.provider')}}</strong>
                                    </label>
                                    <select name="booking_offer_provider_id[]" class="form-control select2 m-b-10" style="width: 100%">
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}"
                                            @if(isset($_filter_params['booking_offer_provider_id']) && in_array($provider->id, $_filter_params['booking_offer_provider_id'])) selected @endif
                                            >{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{__('report.company')}}</strong>
                                    </label>
                                    <select name="booking_offer_company_id[]" class="form-control select2 m-b-10" style="width: 100%">
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                            @if(isset($_filter_params['booking_offer_company_id']) && in_array($company->id, $_filter_params['booking_offer_company_id']) ) selected @endif
                                            >{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{__('report.booking_status')}}</strong>
                                    </label>
                                    <select name="status" class="form-control select2 m-b-10" style="width: 100%">
                                        <option value="">{{ __('messages.select') }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::PENDING ) selected  @endif>{{__('resources.process-statues.pending')}}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::CANCELED ) selected  @endif>{{__('resources.process-statues.canceled')}}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::PAID }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::PAID ) selected  @endif>{{__('resources.process-statues.paid')}}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::CONFIRMED ) selected  @endif>{{__('resources.process-statues.confirmed')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="float-right">
                                <a href="{{ route('backend.reports.bookings.export') }}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-file-excel-o"></i>
                                        {{ __('messages.export') }}
                                </a>
                                <button class="btn btn-sm btn-warning toggle-filter">
                                    <i class="fa fa-filter"></i>
                                    {{ __('messages.filter') }}
                                </button>
                                @if (isset($params))
                                    <a href="{{ route('backend.reports.report_payment_providers.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </td>
    </tr>
</table>
<div class="row" id="pageFilters">

    @if(isset($params) && count($bookings) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning') }}</h4>
                {{ __('messages.no-results') }}
        </div>
    </div>
    @endif
</div>
