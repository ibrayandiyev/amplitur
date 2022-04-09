@php
    $canChange = !(user()->isProvider() && $offer->hasBookings());
    $disabled = $canChange ? null : 'disabled';
    $readonly = $canChange ? null : 'readonly';
    $navigation = old('navigation', "sales-info");
    $active_routes = $active_additionals = $active_sales_info = "";
    switch($navigation){
        default:
        case 'sales-info':
            $active_sales_info  = "active";
            break;
        case 'routes':
            $active_routes      = "active";
            break;
        case 'additionals':
            $active_additionals = "active";
            break;
    }
@endphp

<form id="offerForm" method="post" action="{{ route('backend.providers.companies.offers.update', [$provider, $company, $offer]) }}" autocomplete="off">
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="navigation" value="{{ old('navigation', "sales-info") }}" />
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.bus-trip.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$active_sales_info}}" data-toggle="tab" href="#sales-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bus-trip.sales-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_routes}}" data-toggle="tab" href="#routes" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bustrip-routes.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$active_additionals}}" data-toggle="tab" href="#additionals" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.additionals.name-plural') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane {{$active_sales_info}}" id="sales-info" role="tab-panel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('package_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.packages.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="package_id" class="form-control " {{$disabled}}>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id}}" @if (old('package_id', $offer->package_id) == $package->id) selected @endif>{{ $package->extendedName }}</option>
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
                                <input type="text" class="form-control datetimepicker" name="expires_at" value="{{ old('expires_at', $offer->expiresAtLocal) }}" placeholder="__/__/____, 00:00">
                            </div>
                            <div class="form-group col-md-3  @if($errors->has('currency')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.sales-currency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if($disabled)
                                <input type='hidden' name="currency" value='{{ $offer->currency }}' >
                                <select class="form-control"  disabled>
                                @else
                                <select class="form-control" name="currency" {{$disabled}}>
                                @endif
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                                    <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                                    <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                                    <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3  @if($errors->has('sale_coefficient_id')) has-danger @endif" @if(!user()->canManageOfferSaleCoefficient($offer)) style="display: none;" @endif>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.offers.model.coefficient') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>

                                @if (user()->canManageOfferSaleCoefficient($offer))
                                    <select class="form-control " name="sale_coefficient_id">
                                        <option value>{{ __('messages.select') }}</option>
                                        @foreach ($saleCoefficients as $saleCoefficient)
                                            <option value="{{ $saleCoefficient->id }}" data-coefficient="{{ $saleCoefficient->value }}" @if (old('sale_coefficient_id', $offer->sale_coefficient_id) == $saleCoefficient->id) selected @endif>
                                                {{ App\Models\SaleCoefficient::find($saleCoefficient->id)->extendedName }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control " name="sale_coefficient_id" style="display: none">
                                        <option value="{{ $offer->saleCoefficient->id }}" data-coefficient="{{ $offer->saleCoefficient->value }}" selected>{{ App\Models\SaleCoefficient::find($offer->saleCoefficient->id)->extendedName }}</option>
                                    </select>
                                @endif
                            </div>
                            @if (auth()->user()->isMaster())
                                <div class="form-group col-md-3  @if($errors->has('can_register_additionals')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.offers.model.can_register_additionals') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="can_register_additionals">
                                        <option value="0" @if (old('can_register_additionals', $offer->can_register_additionals) == 0) selected @endif>{{ __('messages.no') }}</option>
                                        <option value="1" @if (old('can_register_additionals', $offer->can_register_additionals) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 @if($errors->has('status')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.offers.model.status') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" name="status">
                                        <option value="{{ \App\Enums\ProcessStatus::IN_ANALYSIS }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::IN_ANALYSIS) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::ACTIVE }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::ACTIVE) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::REFUSED }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::REFUSED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::REFUSED) }}</option>
                                        <option value="{{ \App\Enums\ProcessStatus::SUSPENDED }}" @if (old('status', $offer->status) == \App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.' . \App\Enums\ProcessStatus::SUSPENDED) }}</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane {{$active_routes}}" id="routes" role="tab-panel">
                        @include('backend.offers.types.bus-trip.routes.table')
                        <a href="{{ route('backend.providers.companies.offers.bustrip.createRoute', [$provider, $company, $offer]) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i>
                            {{ __('resources.bustrip-routes.create') }}
                        </a>
                    </div>

                    <div class="tab-pane {{$active_additionals}}" id="additionals" role="tab-panel">
                        @include('backend.offers.types.bus-trip.additionals')
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @if (user()->canUpdateOffer($offer))
                <button type="submit" class="btn btn-primary save">
                    <i class="fa fa-save"></i> {{ __('messages.save') }}
                </button>
            @endif

            <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>

            @if ($canChange)
                <a href="{{ route('backend.providers.companies.offers.destroy', [$provider, $company, $offer]) }}"  class="btn btn-danger delete pull-right">
                    <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                </a>
            @endif
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
