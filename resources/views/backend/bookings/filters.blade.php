<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                <form method="GET" action="{{ route('backend.bookings.filter') }}" autocomplete="off">
                    <div class="row">
                        <div class="form-group col-md-1">
                            <label class="form-control-label">
                                <strong>{{__('report.loc')}}</strong>
                            </label>
                            <input type="text" class="form-control" style="width: 100%; height:36px;" name="id" value="{{ old('id', isset($_params['id'])?$_params['id']:'') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.search_for')}}</strong>
                            </label>
                            <select name="search_for" class="form-control">
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_NAME }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_NAME) selected @endif>{{ __('resources.label.name') }}</option>
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_CPF }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_CPF) selected @endif>{{ __('resources.label.cpf') }}</option>
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_EMAIL }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_EMAIL) selected @endif>{{ __('resources.label.email') }}</option>
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_PHONE }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_PHONE) selected @endif>{{ __('resources.label.phone') }}</option>
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_PASSPORT }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_PASSPORT) selected @endif>{{ __('resources.label.passport') }}</option>
                                <option value="{{ App\Enums\Bookings\BookingSearchFor::FIELD_IDENTITY }}" @if(isset($_params) && $_params['search_for'] == App\Enums\Bookings\BookingSearchFor::FIELD_IDENTITY) selected @endif>{{ __('resources.label.indentity') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.wildcard')}}</strong>
                            </label>
                            <input type="text" class="form-control" style="width: 100%; height:36px;" name="wildcard" value="{{ old('wildcard', isset($_params['wildcard'])?$_params['wildcard']:'') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.provider')}}</strong>
                            </label>
                            <select name="booking_offer_provider_id[]" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}"
                                    @if(isset($_params['booking_offer_provider_id']) && in_array($provider->id, $_params['booking_offer_provider_id'])) selected @endif
                                    >{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.company')}}</strong>
                            </label>
                            <select name="booking_offer_company_id[]" class="form-control select2 m-b-10" style="width: 100%">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                    @if(isset($_params['booking_offer_company_id']) && in_array($company->id, $_params['booking_offer_company_id']) ) selected @endif
                                    >{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-sm btn-warning toggle-filter">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                        <a href="{{ route('backend.reports.bookings.export') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-file-excel-o"></i>
                                {{ __('messages.export') }}
                        </a>
                    </div>

                </form>
            </div>
        </td>
    </tr>
</table>

@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                assumeNearbyYear: true,
                maxViewMode: 2,
            });

        });
    </script>
@endpush
