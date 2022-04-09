<div class="row" id="pageFilters">
    <div class="col-md-12" style="display: none;">
        <div class="card card-outline-inverse">
            <form method="POST" action="{{ route('backend.providers.filter') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    <p class="mb-0 text-white">
                        <strong>{{ __('messages.filters') }}</strong>
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>{{ __('resources.providers.model.name') }}</label>
                            <input type="text" class="form-control" name="name" value="{{ $params['name'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.company-name') }}</label>
                            <input type="text" class="form-control" name="company_name" value="{{ $params['company_name'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.legal-name') }}</label>
                            <input type="text" class="form-control" name="legal_name" value="{{ $params['legal_name'] ?? '' }}" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label>{{ __('resources.providers.model.email') }}</label>
                            <input type="text" class="form-control" name="email" value="{{ $params['email'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.doc-document') }}</label>
                            <input type="text" class="form-control" name="document" value="{{ $params['document'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.doc-identity') }}</label>
                            <input type="text" class="form-control" name="identity" value="{{ $params['identity'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.doc-passport') }}</label>
                            <input type="text" class="form-control" name="passport" value="{{ $params['passport'] ?? '' }}" />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.providers.model.doc-registry') }}</label>
                            <input type="text" class="form-control" name="registry" value="{{ $params['registry'] ?? '' }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{ __('resources.providers.model.created_at') }}</label>
                            <div class="input-group">
                                <input type="text" maxlength="10" class="form-control datepicker" name="created_at[]" value="{{ $params['created_at'][0] ?? '' }}" placeholder="__/__/____" />
                                <span class="input-group-text">{{ __('messages.to') }}</span>
                                <input type="text" maxlength="10" class="form-control datepicker" name="created_at[]" value="{{ $params['created_at'][1] ?? '' }}" placeholder="__/__/____" />
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('resources.address.country') }}</label>
                            <select name="country" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="BR" @if(isset($params) && $params['country'] == 'BR') selected @endif>{{ __('messages.brazil') }}</option>
                                <option value="other" @if(isset($params) && $params['country'] == 'other') selected @endif>{{ __('messages.other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>{{ __('messages.person-type') }}</label>
                            <select name="type" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="{{ App\Enums\PersonType::FISICAL }}" @if(isset($params) && $params['type'] == App\Enums\PersonType::FISICAL) selected @endif>{{ __('messages.person-fisical') }}</option>
                                <option value="{{ App\Enums\PersonType::LEGAL }}" @if(isset($params) && $params['type'] == App\Enums\PersonType::LEGAL) selected @endif>{{ __('messages.person-legal') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button>
                    @if (isset($params))
                        <a href="{{ route('backend.providers.index') }}" class="btn btn-warning btn-sm float-right">{{ __('messages.remove-filters') }}</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($params) && count($providers) == 0)
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
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            maxViewMode: 2,
        });
    </script>
@endpush
