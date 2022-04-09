<table id="filter" class="table color-bordered-table warning-bordered-table">
    <thead>
        <tr>
            <th>Filtro</th>
        </tr>
    </thead>
    <tr>
        <td>
            <div class="tab-pane active" id="basic-info" role="">
                    <form id="packageForm" method="get" action="{{ route("backend.packages.index")}}"  autocomplete="off">
                        <div class="row">
                            <div class="form-group col-md-10">
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
