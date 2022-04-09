@php
    $companyAddress = $company->address;
    $companyCity = ($companyAddress) ? $companyAddress->city() : null;
    $companyState = ($companyAddress) ? $companyAddress->state() : null;
    $companyCountry = ($companyAddress) ? $companyAddress->country() : null;
    $companyFinancialContactInfo = $company->financialContactInfo;
    $companyBookingContactInfo = $company->bookingContactInfo;
@endphp
@extends('backend.template.default')
@section('content')
@php  $disabled = ""; @endphp
@cannot('manage', $provider)
@php  $disabled = "disabled"; @endphp
@endcannot
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.companies.edit') }}</h3>
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
            <li class="breadcrumb-item active">{{ $company->company_name }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.providers.companies.offers.prepare', [$provider, $company]) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-plus"></i>
                {{ __('resources.offers.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="companyForm" method="post" action="{{ route('backend.providers.companies.update', [$provider, $company]) }}" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="_method" value="put" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.companies.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.companies.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#offers" role="tab">
                            <span class="hidden-sm-up"><i class="ti-folder"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.offers.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#logs" role="tab">
                            <span class="hidden-sm-up"><i class="ti-calendar"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.logs.logs') }}</span>
                        </a>
                    </li>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <H3 class="card-title">{{ __('resources.label_geral') }}</H3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-5 @if($errors->has('company_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.company-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="company_name" value="{{ old('company_name', $company->company_name) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('legal_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.legal-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="legal_name" value="{{ old('legal_name', $company->legal_name) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('registry')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.doc-registry') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="registry" value="{{ old('registry', $company->registry) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('website')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.website') }}</strong>
                                </label>
                                <input type="url" class="form-control text-lowercase" name="website" value="{{ old('website', $company->website) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language" required>
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language', $company->language) == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language', $company->language) == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language', $company->language) == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('status')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.status') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="status" required  {{ $disabled }}>
                                    @if(user()->canManageProviders())
                                    <option value="{{ App\Enums\ProcessStatus::ACTIVE }}" @if(old('status', $company->status) == App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.active') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::SUSPENDED }}" @if(old('status', $company->status) == App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.suspended') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::IN_ANALYSIS }}" @if(old('status', $company->status) == App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.in-analysis') }}</option>
                                    @else
                                        <option value="{{ $company->status }}" >{{$company->getStatusTitle()}} </option>
                                    @endif
                                </select>
                            </div>
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
                                    <option value="{{ $country->iso2 }}" @if($country->iso2 == old('address.country', $company->country)) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[state]"  {{ $disabled }}></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[city]"  {{ $disabled }}></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                    <span class="text-danger" data-brazil-only>*</span>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', $companyAddress->zip ?? null) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', $companyAddress->address ?? null) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', $companyAddress->number ?? null) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }} <span class="text-danger" data-brazil-only>*</span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', $companyAddress->neighborhood ?? null) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', $companyAddress->complement ?? null) }}" {{ $disabled }}>
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
                                <input type="text" name="contacts[responsible][]" class="form-control " value="{{ old('contacts.responsible.0', $companyFinancialContactInfo->responsible) }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.0')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.phone') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <br />
                                <input type="hidden" name="contacts[id][0]" value="{{ $companyFinancialContactInfo->phone_contact_id }}" />
                                <input type="hidden" name="contacts[type][]" value="financial-phone" />
                                <input type="tel" class="form-control phone-flag" name="contacts[value][]" value="{{ old('contacts.value.0', $companyFinancialContactInfo->phone) }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.1')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="contacts[type][]" value="financial-email" />
                                <input type="hidden" name="contacts[id][1]" value="{{ $companyFinancialContactInfo->email_contact_id }}" />
                                <input type="email" class="form-control text-lowercase" name="contacts[value][]" value="{{ old('contacts.value.1', $companyFinancialContactInfo->email) }}" />
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
                                <input type="text" name="contacts[responsible][]" class="form-control " value="{{ old('contacts.responsible.1', $companyBookingContactInfo->responsible) }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.2')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.phone') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <br />
                                <input type="hidden" name="contacts[id][2]" value="{{ $companyBookingContactInfo->phone_contact_id }}" />
                                <input type="hidden" name="contacts[type][]" value="booking-phone" />
                                <input type="tel" class="form-control phone-flag" name="contacts[value][]" value="{{ old('contacts.value.2', $companyBookingContactInfo->phone) }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('contacts.value.3')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.companies.model.responsibles.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="hidden" name="contacts[id][3]" value="{{ $companyBookingContactInfo->email_contact_id }}" />
                                <input type="hidden" name="contacts[type][]" value="booking-email" />
                                <input type="email" class="form-control text-lowercase" name="contacts[value][]" value="{{ old('contacts.value.3', $companyBookingContactInfo->email) }}" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.documents.name-plural') }}</strong>
                                </label>
                                <table class="table table-bordered table-striped table-hover dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>{{ __('resources.documents.model.filename') }}</th>
                                            <th>{{ __('resources.documents.model.status') }}</th>
                                            <th>{{ __('resources.documents.model.created_at') }}</th>
                                            <th>{{ __('resources.documents.model.updated_at') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $document)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('backend.providers.companies.documents.show', [$provider, $company, $document]) }}" target="_blank">{{ $document->filename }}</a>
                                                </td>
                                                <td>{!! $document->statusLabel !!}</td>
                                                <td>
                                                    {{ $document->createdAtLabel }}
                                                    <small class="text-muted">{{ $document->createdAtTimeLabel }}</small>
                                                </td>
                                                <td>
                                                    {{ $document->updatedAtLabel }}
                                                    <small class="text-muted">{{ $document->updatedAtTimeLabel }}</small>
                                                </td>
                                                <td class="text-center">
                                                    @if ($document->isInAnalysis())
                                                        @if(user()->canManageCompanies())
                                                        <a href="{{ route('backend.providers.companies.documents.accept', [$provider, $company, $document]) }}"  class="btn btn-success btn-sm btn-save" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-approve') }}">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                        <a href="{{ route('backend.providers.companies.documents.decline', [$provider, $company, $document]) }}"  class="btn btn-warning btn-sm btn-save" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-refuse') }}">
                                                            <i class="fa fa-ban"></i>
                                                        </a>
                                                        @endif

                                                    @else
                                                    <a href="{{ route('backend.providers.companies.documents.inAnalysis', [$provider, $company, $document]) }}"  class="btn btn-primary btn-sm btn-save" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-analysis') }}">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                    <a href="{{ route('backend.providers.companies.documents.show', [$provider, $company, $document]) }}"  class="btn btn-primary btn-sm btn-save" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-analysis') }}">
                                                        <i class="fa fa-search"></i>
                                                    </a>

                                                    @endif
                                                    @if(user()->canManageCompanies() || (App\Enums\ProcessStatus::ACTIVE != $company->status) )
                                                        <a href="{{ route('backend.providers.companies.documents.destroy', [$provider, $company, $document]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('resources.process-statues.to-delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($document->isInAnalysis())
                            <div class="row">
                                <div class="form-group col-md-12 @if($errors->has('documents')) has-danger @endif">
                                    <input id="documents" name="documents[]" type="file" class="form-control" multiple />
                                </div>
                            </div>
                        @endif

                        <H3 class="card-title">{{ __('resources.label_bank') }}</H3>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-3  @if($errors->has('bank_account.currency')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.currency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if(user()->canManageCompanies())
                                <select class="form-control" name="bank_account[currency]" data-currency-selector  {{ $disabled }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ \App\Enums\Currency::REAL }}" @if (old('bank_account.currency', $company->bankAccount->currency ?? '') == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                                    <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('bank_account.currency', $company->bankAccount->currency ?? '') == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                                    <option value="{{ \App\Enums\Currency::EURO }}" @if (old('bank_account.currency', $company->bankAccount->currency ?? '') == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                                    <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('bank_account.currency', $company->bankAccount->currency ?? '') == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                                </select>
                                @else
                                <div>{{ $company->bankAccount->currency }}</div>
                                <input type="hidden" name="bank_account[currency]" value="{{ $company->bankAccount->currency }}" readonly/>
                                @endif
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
                                        <option value="{{ $bank->code }}" @if (old('bank_account.BRL.bank', $company->bankAccount->bank ?? '') == $bank->code) selected @endif>{{ $bank->code }} - {{ $bank->name }}</option>
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
                                    <option value="current" @if (old('bank_account.BRL.account_type', $company->bankAccount->account_type ?? '') == 'current') selected @endif>{{ __('resources.financial.account-types.current') }}</option>
                                    <option value="savings" @if (old('bank_account.BRL.account_type', $company->bankAccount->account_type ?? '') == 'savings') selected @endif>{{ __('resources.financial.account-types.savings') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.agency') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[BRL][agency]" value="{{ old('bank_account.BRL.agency', $company->bankAccount->agency ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[BRL][account_number]" value="{{ old('bank_account.BRL.account_number', $company->bankAccount->account_number ?? '') }}" />
                            </div>
                        </div>

                        <!-- USD -->
                        <div class="row" data-display-currency="USD" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.bank-swift-bic') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][bank]" value="{{ old('bank_account.USD.bank', $company->bankAccount->bank ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.wire') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][wire]" value="{{ old('bank_account.USD.wire', $company->bankAccount->wire ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.routing') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][routing_number]" value="{{ old('bank_account.USD.routing_number', $company->bankAccount->routing_number ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[USD][account_number]" value="{{ old('bank_account.USD.account_number', $company->bankAccount->account_number ?? '') }}" />
                            </div>
                        </div>

                        <!-- GBP -->
                        <div class="row" data-display-currency="GBP" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.iban') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][iban]" value="{{ old('bank_account.GBP.iban', $company->bankAccount->iban ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.sort-code') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][sort_code]" value="{{ old('bank_account.GBP.sort_code', $company->bankAccount->sort_code ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.account') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[GBP][account_number]" value="{{ old('bank_account.GBP.account_number', $company->bankAccount->account_number ?? '') }}" />
                            </div>
                        </div>

                        <!-- EUR -->
                        <div class="row" data-display-currency="EUR" style="display: none">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.bank-swift-bic') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[EUR][bank]" value="{{ old('bank_account.EUR.bank', $company->bankAccount->bank ?? '') }}" />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.financial.iban') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="bank_account[EUR][iban]" value="{{ old('bank_account.EUR.iban', $company->bankAccount->iban ?? '') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="offers" role="tabpanel">
                        @include('backend.offers.table')
                    </div>

                    <div class="tab-pane" id="logs" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-10">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.logs.message') }}</strong>
                                </label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group col-md-2 mt-30">
                                <button class="btn btn-block btn-sm btn-primary">
                                    <i class="fa fa-plus-circle"></i>
                                    {{ __('resources.logs.create') }}
                                </button>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class=col-md-2>
                                <small>
                                    29/04/2020 <i class="fa fa-clock-o"></i> 08:09:21 <br />
                                    <span class="label label-light-inverse">{{ __('resources.logs.system') }}</span>
                                </small>
                            </div>
                            <div class="col-md-10">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class=col-md-2>
                                <small>
                                    29/04/2020 <i class="fa fa-clock-o"></i> 08:09:21 <br />
                                    <span class="label label-light-inverse">{{ __('resources.logs.system') }}</span>
                                </small>
                            </div>
                            <div class="col-md-10">
                                <p>It is a long established fact that a reader will be distracted</p>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <div class=col-md-2>
                                <small>
                                    29/04/2020 <i class="fa fa-clock-o"></i> 08:09:21 <br />
                                    <span class="label label-light-primary">USERNAME</span>
                                </small>
                            </div>
                            <div class="col-md-10">
                                <p>There are many variations of passages of Lorem Ipsum available</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.providers.companies.index', $provider) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                    <a href="{{ route('backend.providers.companies.destroy', [$provider, $company]) }}" token="{{ csrf_token() }}" class="btn btn-danger delete pull-right">
                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
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
                country: "{{ old('address.country', $companyCountry) }}",
                state: "{{ old('address.state', $companyState->iso2 ?? $companyState) }}",
                city: "{{ old('address.city', $companyCity->id ?? $companyCity) }}"
            });

            handleDisplayCurrency("{{ $company->bankAccount->currency ?? '' }}");

            $('#offersTable').DataTable().destroy();
        });
    </script>
@endpush
