<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>{{ __('messages.filters') }}</th>
        </tr>
    </thead>
    <tr>
        <td>
            <form method="GET" action="{{ route('backend.reports.report_stock.index') }}" autocomplete="off">
                <div class="tab-pane active" id="basic-info" role="">
                    <div class="row">
                        <div class="form-group col-md-4">
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
                        @if($master)
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.provider')}}</strong>
                            </label>
                            <select name="provider_id" id="provider_id" class="form-control ">
                                <option data-json="[]" value="0">{{ __("report.select_option")}}</option>
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id}}" 
                                    @if (old('provider_id', $_filter_params['provider_id'] ?? null) == $provider->id) selected @endif
                                    data-json='{{ json_encode($provider->companies()->pluck('company_name', 'id')) }}'
                                    >{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.company')}}</strong>
                            </label>
                            <select name="company_id" id="company_id" class="form-control ">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id}}" @if (old('company_id', $_filter_params['company_id'] ?? null) == $company->id) selected @endif>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.offer_type')}}</strong>
                            </label>
                            <select name="offer_type_id" class="form-control select2 m-b-10" style="width: 100%">
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
                        <a href="{{ route('backend.reports.bookings.export') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-file-excel-o"></i>
                                {{ __('messages.export') }}
                        </a>
                        <button class="btn btn-sm btn-warning toggle-filter">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                    </div>

                </div>
            </form>
        </td>
    </tr>
</table>
@push('scripts')
    <script type="text/javascript">
        var selectedCompanyId = "{{ $_filter_params['company_id'] ?? null}}";
        function changeProvider(stringJson){
            var jsonProvider= JSON.parse(stringJson); 
            var $select = $('#company_id');
            $select.find('option').remove(); 
            $('<option>').val(0).text("{{ __('report.select_option')}}").appendTo($select);
            $.each(jsonProvider, function(key, value) {
                var $option = $('<option>').val(key).text(value);
                if(selectedCompanyId == key){
                    $option = $option.attr("selected", "selected");
                }
                $option.appendTo($select);
            });
        }
        $("#provider_id").on("change", function(){
            changeProvider($(this).find("option:selected").attr("data-json"));
        });
        $(document).ready(function(){
            changeProvider($("#provider_id").find("option:selected").attr("data-json"));
        });

    </script>
@endpush