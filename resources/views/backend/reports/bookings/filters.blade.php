<div class="row" id="pageFilters">
    <div class="col-md-12" @if(!isset($_filter_params) || !is_array($_filter_params)) style=" display: none; "@endif>
        <div class="card card-outline-inverse">
            <form method="GET" action="{{ route('backend.reports.reports.bookings.index') }}" autocomplete="off">
                <div class="labelx label-service">
                    <p class="mb-0 text-white">
                        <strong>{{ __('messages.filters') }}</strong>
                    </p>
                </div>
                @include('backend.bookings.filter_body')

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                    @if (isset($params))
                        <a href="{{ route('backend.reports.reports.bookings.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($params) && count($bookings) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning') }}</h4>
                {{ __('messages.no-results') }}
        </div>
    </div>
    @endif
</div>
