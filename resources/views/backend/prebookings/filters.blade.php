<div class="row" id="pageFilters">
    <div class="col-md-12" style="display: none;">
        <div class="card card-outline-inverse">
            <form method="POST" action="{{ route('backend.prebookings.filter') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    <p class="mb-0 text-white">
                        <strong>{{ __('messages.filters') }}</strong>
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">
                                <strong>{{ __('resources.events.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="event_id" style="width: 100%;">
                                @csrf
                            </select>
                        </div>
                        <div class="col-md-8 form-group">
                            <label>{{ __('resources.clients.model.created_at') }}</label>
                            <div class="input-group">
                                <input type="text" maxlength="10" class="form-control datepicker" name="created_at[]" value="{{ $params['created_at'][0] ?? '' }}" placeholder="__/__/____" />
                                <span class="input-group-text">{{ __('messages.to') }}</span>
                                <input type="text" maxlength="10" class="form-control datepicker" name="created_at[]" value="{{ $params['created_at'][1] ?? '' }}" placeholder="__/__/____" />
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ __('resources.address.country') }}</label>
                            <select name="country" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}">{{ country($country->iso2) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                    @if (isset($params))
                        <a href="{{ route('backend.prebookings.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($params) && count($prebookings) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning') }}</h4>
                {{ __('messages.no-results') }}
        </div>
    </div>
    @endif
</div>

@push('styles')
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    @include('backend.template.scripts.select-events')
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
    </script>
@endpush
