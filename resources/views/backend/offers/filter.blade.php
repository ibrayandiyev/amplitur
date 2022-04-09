@php 
if(!isset($packages) || !isset($providers) || !isset($companies)){
    return false;
}
@endphp
<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                    <form id="packageForm" method="get" action="{{ route("backend.offers.index")}}" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-control-label">
                                    <strong>{{__('report.package')}}</strong>
                                </label>
                                <select name="package_id" class="form-control select2 m-b-10">
                                    <option value="-1">{{ __("report.select_option")}}</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id}}" @if (old('package_id', $_params['package_id'] ?? null) == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{__('report.provider')}}</strong>
                                </label>
                                <select name="provider_id" class="form-control ">
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id}}" @if (old('provider_id', $_params['provider_id'] ?? null) == $provider->id) selected @endif>{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{__('report.company')}}</strong>
                                </label>
                                <select name="company_id" class="form-control ">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id}}" @if (old('company_id', $_params['company_id'] ?? null) == $company->id) selected @endif>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="float-right">
                            <button class="btn btn-sm btn-warning toggle-filter">
                                <i class="fa fa-filter"></i>
                                {{ __('messages.filter') }}
                            </button>
                        </div>
                    </form>
            </div>
        </td>
    </tr>
</table>
