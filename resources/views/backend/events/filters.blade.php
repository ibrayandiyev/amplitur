<div class="row" id="pageFilters">
    <div class="col-md-12" style="display: none;">
        <div class="card card-outline-inverse">
            <form method="POST" action="{{ route('backend.events.filter') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    <p class="mb-0 text-white">
                        <strong>{{ __('messages.filters') }}</strong>
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>{{ __('resources.events.model.name') }}</label>
                            <input type="text" class="form-control" name="name" value="{{ $params['name'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.events.model.country') }}</label>
                            <select name="country" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="BR" @if(isset($params) && $params['country'] == 'BR') selected @endif>{{ __('messages.brazil') }}</option>
                                <option value="other" @if(isset($params) && $params['country'] == 'other') selected @endif>{{ __('messages.other') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                    @if (isset($params))
                        <a href="{{ route('backend.events.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($params) && count($events) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('messages.warning') }}</h4>
                {{ __('messages.no-results') }}
        </div>
    </div>
    @endif
</div>
