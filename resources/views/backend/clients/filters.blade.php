<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                <form id="filter_form" method="GET" action="{{ route('backend.clients.filter') }}" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="form-control-label">
                                <strong>{{__('report.search_for')}}</strong>
                            </label>
                            <select name="search_for" class="form-control">
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_NAME }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_NAME) selected @endif>{{ __('resources.label.name') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_CPF }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_CPF) selected @endif>{{ __('resources.label.cpf') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_EMAIL }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_EMAIL) selected @endif>{{ __('resources.label.email') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_LOGIN }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_LOGIN) selected @endif>{{ __('resources.label.login') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_PHONE }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_PHONE) selected @endif>{{ __('resources.label.phone') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_PASSPORT }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_PASSPORT) selected @endif>{{ __('resources.label.passport') }}</option>
                                <option value="{{ App\Enums\Clients\ClientSearchFor::FIELD_IDENTITY }}" @if(isset($params) && $params['search_for'] == App\Enums\Clients\ClientSearchFor::FIELD_IDENTITY) selected @endif>{{ __('resources.label.indentity') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="form-control-label">
                                <strong>{{__('report.wildcard')}}</strong>
                            </label>
                            <input type="text" class="form-control" style="width: 100%; height:36px;" name="wildcard" value="{{ old('wildcard', isset($params['wildcard'])?$params['wildcard']:'') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.start_date')}}</strong>
                            </label>
                            <input type="text" class="form-control datepicker" style="width: 100%; height:36px;" name="start_date" value="{{ old('start_date', isset($params['start_date'])?$params['start_date']:'') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="form-control-label">
                                <strong>{{__('report.end_date')}}</strong>
                            </label>
                            <input type="text" class="form-control datepicker" style="width: 100%; height:36px;" name="end_date" value="{{ old('end_date', isset($params['end_date'])?$params['end_date']:'') }}">
                        </div>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-sm btn-warning toggle-filter">
                            <i class="fa fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                        <button class="btn btn-sm btn-warning toggle-filter btn-filter" type="submit" name="email_list" value="1" target='_blank'>
                            <i class="fa fa-file-excel-o"></i>
                                {{ __('resources.label.email_list') }}
                        </button>
                        <button class="btn btn-sm btn-warning toggle-filter btn-filter" type="submit" name="exportar" value="1" target='_blank'>
                            <i class="fa fa-file-excel-o"></i>
                                {{ __('messages.export_registers') }}
                        </button>
                        <a href="{{ route('backend.clients.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-file-excel-o"></i>
                                {{ __('resources.label.claer_filter') }}
                        </a>
                    </div>

                </form>

            </div>
        </td>
    </tr>
</table>

@push('styles')
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
        $('.btn-filter').on('click', function(){
            $("#filter_form").attr("target", "_blank");
            setTimeout(function(){
                $("#filter_form").attr("target", "");
            },2000)
        });
    </script>
@endpush
