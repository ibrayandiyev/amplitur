@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.companies.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.edit', $provider) }}">{{ $provider->name }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.index', $provider) }}">{{ __('resources.companies.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.companies.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.providers.companies.create', $provider) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.companies.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="companyForm" method="post" action="{{ route('backend.providers.companies.store', $provider) }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.companies.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">

                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <H3 class="card-title">{{ __('resources.label_geral') }}</H3>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-5 @if($errors->has('legal_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.legal-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="legal_name" value="{{ old('legal_name') }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('company_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.company-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="company_name" value="{{ old('company_name') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('registry')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.doc-registry') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="registry" value="{{ old('registry') }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('website')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.website') }}</strong>
                                </label>
                                <input type="url" class="form-control text-lowercase" name="website" value="{{ old('website') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language" required>
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language') == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language') == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language') == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
                                </select>
                            </div>
                            @if(user()->canManageProviders())
                            <div class="form-group col-md-3 @if($errors->has('status')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.status') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="status" required>
                                    <option value="{{ App\Enums\ProcessStatus::IN_ANALYSIS }}" @if(old('status') == App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.in-analysis') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::ACTIVE }}" @if(old('status') == App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.active') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::SUSPENDED }}" @if(old('status') == App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.suspended') }}</option>
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="status" value="{{ App\Enums\ProcessStatus::IN_ANALYSIS }}" />
                            @endif
                        </div>

                        <H3 class="card-title">{{ __('resources.label_address') }}</H3>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.country') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[country]" >
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if($country->iso2 == old('address.country')) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[state]" disabled></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[city]" disabled></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                    <span class="text-danger" data-brazil-only>*</span>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip') }}">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address') }}">
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }} <span class="text-danger" data-brazil-only>*</span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement') }}">
                            </div>
                        </div>
                        <H3 class="card-title">{{ __('resources.label_contact') }}</H3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>{{ __('resources.companies.model.financial-responsible') }}</h6>
                                <hr class="m-t-20 m-b-20 hr-dashed"/>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('contacts.responsible.0')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contacts[responsible][]" class="form-control " value="{{ old('contacts.responsible.0') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.0')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.phone') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <br />
                                <input type="hidden" name="contacts[type][]" value="financial-phone" />
                                <input type="tel" class="form-control phone-flag" name="contacts[value][]" value="{{ old('contacts.value.0') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.1')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="contacts[type][]" value="financial-email" />
                                <input type="email" class="form-control text-lowercase" name="contacts[value][]" value="{{ old('contacts.value.1') }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>{{ __('resources.companies.model.booking-responsible') }}</h6>
                                <hr class="m-t-20 m-b-20 hr-dashed"/>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('contacts.responsible.1')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contacts[responsible][]" class="form-control " value="{{ old('contacts.responsible.1') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.2')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.phone') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <br />
                                <input type="hidden" name="contacts[type][]" value="booking-phone" />
                                <input type="tel" class="form-control phone-flag" name="contacts[value][]" value="{{ old('contacts.value.2') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.3')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="contacts[type][]" value="booking-email" />
                                <input type="email" class="form-control text-lowercase" name="contacts[value][]" value="{{ old('contacts.value.3') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('documents')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.documentation') }}</strong><br>
                                    <span style="color: #ff0606">{{ __('resources.companies.model.documentation_obs') }}
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input id="documents" name="documents[]" type="file" class="form-control" multiple />
                            </div>
                        </div>
                    </div>

                    <H3 class="card-title">{{ __('resources.label_bank') }}</H3>
                    <hr>

                        <div class="row">
                            <div class="form-group col-md-3  @if($errors->has('bank_account.currency')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.currency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="bank_account[currency]" data-currency-selector>
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ \App\Enums\Currency::REAL }}" @if (old('bank_account.currency') == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                                    <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('bank_account.currency') == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                                    <option value="{{ \App\Enums\Currency::EURO }}" @if (old('bank_account.currency') == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                                    <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('bank_account.currency') == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- BRL -->
                        <div class="row" data-display-currency="BRL" style="display: none">
                            <div class="form-group col-md-4">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.bank') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="bank_account[BRL][bank]">
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($brazilianBanks as $bank)
                                        <option value="{{ $bank->code }}" @if (old('bank_account.BRL.bank') == $bank->code) selected @endif>{{ $bank->code }} - {{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account-type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="bank_account[BRL][account_type]">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="current" @if (old('bank_account.BRL.account_type') == 'current') selected @endif>{{ __('resources.financial.account-types.current') }}</option>
                                    <option value="savings" @if (old('bank_account.BRL.account_type') == 'savings') selected @endif>{{ __('resources.financial.account-types.savings') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.agency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[BRL][agency]" value="{{ old('bank_account.BRL.agency') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[BRL][account_number]" value="{{ old('bank_account.BRL.account_number') }}" />
                            </div>
                        </div>

                        <!-- USD -->
                        <div class="row" data-display-currency="USD" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.bank-swift-bic') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][bank]" value="{{ old('bank_account.USD.bank') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.wire') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][wire]" value="{{ old('bank_account.USD.wire') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.routing') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][routing_number]" value="{{ old('bank_account.USD.routing_number') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][account_number]" value="{{ old('bank_account.USD.account_number') }}" />
                            </div>
                        </div>

                        <!-- GBP -->
                        <div class="row" data-display-currency="GBP" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.iban') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][iban]" value="{{ old('bank_account.GBP.iban') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.sort-code') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][sort_code]" value="{{ old('bank_account.GBP.sort_code') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][account_number]" value="{{ old('bank_account.GBP.account_number') }}" />
                            </div>
                        </div>

                        <!-- EUR -->
                        <div class="row" data-display-currency="EUR" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.bank-swift-bic') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[EUR][bank]" value="{{ old('bank_account.EUR.bank') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.iban') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[EUR][iban]" value="{{ old('bank_account.EUR.iban') }}" />
                            </div>
                        </div>

                    <div class="controls">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" required="" value="1" class="custom-control-input" name="terms_use" id="terms_use" aria-invalid="false">
                            <label class="custom-control-label" for="terms_use">{!! __('resources.terms_service_company') !!} </label>
                        </div>
                        
                    <div class="help-block"></div>


                </div>
                    </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.providers.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/backend/js/resources/personable.js"></script>
    <script src="/backend/js/resources/currency.js"></script>
    <script src="/backend/js/resources/companies.resource.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            catchFormSubmit($('#companyForm'));

            fillAddress({
                country: "{{ old('address.country') }}",
                state: "{{ old('address.state') }}",
                city: "{{ old('address.city') }}"
            });

            handleDisplayCurrency("{{ old('bank_account.currency') }}");
        });
    </script>
@endpush
