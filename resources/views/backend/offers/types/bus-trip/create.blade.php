<form id="offerForm" method="post" action="{{ route('backend.providers.companies.offers.store', [$provider, $company]) }}?event_id={{$event->id}}&type={{$type}}" autocomplete="off">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.bus-trip.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#sales-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bus-trip.sales-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="sales-info" role="tab-panel">
                        <div class="alert alert-info">
                            {{ __('messages.the_event') }} <span class=" font-weight-bold">{{ $event->name }}</span> {{ __('messages.linked_offer') }} <a href="{{ route('backend.packages.create', [$provider]) }}?provider_id={{ $provider->id }}&company_id={{ $company->id }}&event_id={{ $event->id }}&offerType={{ $type }}">{{ __('messages.created_new') }}</a>.
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('package_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.packages.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="package_id" class="form-control ">
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id}}" @if (old('package_id', $selectedPackage->id ?? null) == $package->id) selected @endif>{{ $package->extendedName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('expires_at')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.expires_at') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="17" class="form-control datetimepicker" name="expires_at" value="{{ old('expires_at') }}" placeholder="__/__/____, 00:00">
                            </div>
                            <div class="form-group col-md-3  @if($errors->has('currency')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.sales-currency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="currency">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency') == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                                    <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency') == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                                    <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency') == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                                    <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency') == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                                </select>
                            </div>
                            @if (auth()->user()->isMaster())
                                <div class="form-group col-md-3  @if($errors->has('coefficient')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.offers.model.coefficient') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <p>{{ $defaultSaleCoefficient->value }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary save">
                <i class="fa fa-save"></i> {{ __('messages.save') }}
            </button>
            <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>
        </div>
    </div>
</form>

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            mask: true
        });
    </script>
@endpush
