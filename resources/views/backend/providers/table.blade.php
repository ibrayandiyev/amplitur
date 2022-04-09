<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                    <form id="packageForm" method="post" action="" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{__('report.search_for')}}</strong>
                                </label>
                                <input type="text" class="select2 form-control custom-select select2-hidden-accessible" style="width: 100%; height:36px;" name="" value="teste">
                            </div>
                            <div class="form-group col-md-5">
                                <label class="form-control-label">
                                    <strong>{{__('report.wildcard')}}</strong>
                                </label>
                                <input type="text" class="form-control" style="width: 100%; height:36px;" name="" value="">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{__('report.start_date')}}</strong>
                                </label>
                                <input type="date" class="form-control" style="width: 100%; height:36px;" name="" value="">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{__('report.end_date')}}</strong>
                                </label>
                                <input type="date" class="form-control" style="width: 100%; height:36px;" name="" value="">
                            </div>
                        </div>
                        <div class="float-right">
                            <button class="btn btn-sm btn-warning toggle-filter">
                                <i class="fa fa-filter"></i>
                                {{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('backend.reports.bookings.export') }}" class="btn btn-sm btn-secondary">
                                <i class="fa fa-file-excel-o"></i>
                                 {{ __('messages.export_registers') }}
                            </a>
                        </div>

                    </form>
            </div>
        </td>
    </tr>
</table>

<table id="providersTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="30%">{{ __('resources.providers.model.name') }}</th>
            <th width="19%">{{ __('resources.address.city') }}</th>
            <th width="15%">{{ __('resources.address.country') }}</th>
            <th width="15%" class="text-center">{{ __('resources.providers.model.created_at') }}</th>
            <th width="10%" class="text-center">{{ __('resources.providers.model.type') }}</th>
            <th width="5%" class="text-center">{{ __('resources.providers.model.status') }}</th>
            <th width="5%" class="text-center">{{ __('resources.providers.model.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($providers as $provider)
            @php
                $address = $provider->address;
                $city = ($address) ? $address->city() : null;
                $state = ($address) ? $address->state() : null;
                $country = ($address) ? $address->country() : null;
            @endphp
            <tr>
                <td class="">
                    <a href="{{ route('backend.providers.edit', $provider->id) }}">{{ name($provider) }}</a>
                </td>
                <td class="">
                    {{ city($city) }} ({{ state($country, $state) }})
                </td>
                <td class="">
                    {{ country($country) }}
                </td>
                <td class="text-center">
                    {{ $provider->createdAtLabel }} -                    {{ $provider->createdAtTimeLabel }}
                </td>
                <td class="text-center">{!! $provider->typeLabel !!}</td>
                <td class="text-center">{!! $provider->statusLabel !!}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.providers.companies.index', $provider) }}"  class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.companies.name-plural') }}">
                        <i class="fa fa-institution"></i>
                    </a>
                    <a href="{{ route('backend.providers.destroy', $provider) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </tr>
            </td>
        @endforeach
    </tbody>
</table>
