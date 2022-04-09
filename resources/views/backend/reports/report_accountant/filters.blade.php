@php

@endphp
<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>{{ __('messages.filters') }}</th>
        </tr>
    </thead>
    <tr>
        <td>
            <form method="GET" action="{{ route('backend.reports.report_accountant.index') }}" autocomplete="off">
                <div class="tab-pane active" id="basic-info" role="">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label">
                                <strong>{{__('report.package')}}</strong>
                            </label>
                            <select name="package_id" class="form-control select2 m-b-10">
                                <option value="-1">{{ __("report.select_option")}}</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id}}" @if (old('package_id', $selectedPackage->id ?? null) == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">
                                <strong>{{__('report.provider')}}</strong>
                            </label>
                            <select name="provider_id" class="form-control ">
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id}}" @if (old('provider_id', $_filter_params['provider_id'] ?? null) == $provider->id) selected @endif>{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.booking')}}</strong>
                            </label>
                            <input type="text" name="booking_id" class="form-control" style="width: 100%; height:36px;" name="" value="{{ old('booking_id', $_filter_params['booking_id'] ?? null) }}" />
                        </div>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-sm btn-warning">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </td>
    </tr>
</table>
