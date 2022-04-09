<div class="card">
    <div class="card-body">

            <table class="table color-bordered-table warning-bordered-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.filters') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="tab-pane active" id="basic-info" role="">
                                <form method="GET" action="{{ route('backend.offers.index') }}" autocomplete="off">
                                                <div class="col-md-10 form-group">
                                                    <input type='hidden' name='analytic' value='1' />
                                                    <label>{{ __('resources.packages.name') }}</label>
                                                    <select id="select-analytic" name="package" class="form-control select2 m-b-10">
                                                        <option value="">{{ __('messages.select') }}</option>
                                                        @php
                                                            $packageFilters = $packageFilters->sortBy("event.name");
                                                        @endphp
                                                        @foreach($packageFilters as $package)
                                                        <option value="{{ $package->id }}"
                                                            @if(isset($params['package']) && $params['package'] == $package->id) selected @endif >
                                                            {{ $package->extendedName}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                    <div class="float-right">
                                                        <button id="filter-button" type="submit" class="btn btn-sm btn-warning">
                                                            <i class="fa fa-filter"></i>{{ __('messages.filter') }}</button>
                                                    </div>

                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
    </div>
</div>


@push('styles')
@endpush

@push('scripts')

@endpush
