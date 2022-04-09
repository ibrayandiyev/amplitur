<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>{{ __('messages.filters') }}</th>
        </tr>
    </thead>
    <tr>
        <td>
            <form method="GET" action="{{ route('backend.reports.report_email.index') }}" autocomplete="off">
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
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.booking_status')}}</strong>
                            </label>
                            <select name="status" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PENDING }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::PENDING ) selected  @endif>{{ \App\Enums\ProcessStatus::PENDING }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CANCELED }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::CANCELED ) selected  @endif>{{ \App\Enums\ProcessStatus::CANCELED }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::PAID }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::PAID ) selected  @endif>{{ \App\Enums\ProcessStatus::PAID }}</option>
                                <option value="{{ \App\Enums\ProcessStatus::CONFIRMED }}" @if(isset($_filter_params['status']) && $_filter_params['status'] == \App\Enums\ProcessStatus::CONFIRMED ) selected  @endif>{{ \App\Enums\ProcessStatus::CONFIRMED }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                        @if (isset($_filter_params))
                            <a href="{{ route('backend.reports.report_email.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                        @endif
                    </div>
                </div>
            </form>
        </td>
    </tr>
</table>
