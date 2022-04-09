@extends('backend.template.default')
@section('content')
@php  $disabled = ""; @endphp
@cannot('manage', $provider)
@php  $disabled = "readonly"; @endphp
@endcannot
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.providers.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $provider->name }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            @can('manage', $provider)
            <a href="{{ route('backend.providers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.providers.create') }}
            </a>
            @endcannot
            <a href="{{ route('backend.providers.companies.create', $provider) }}" class="btn btn-sm btn-secondary">
                <i class="fa fa-plus"></i>
                {{ __('resources.companies.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="providerForm" method="post" action="{{ route('backend.providers.update', $provider) }}" autocomplete="off">
                <input type="hidden" name="_method" value="put" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.providers.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.providers.basic-info') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#companies" role="tab">
                            <span class="hidden-sm-up"><i class="ti-ticket"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.companies.name-plural') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#logs" role="tab">
                            <span class="hidden-sm-up"><i class="ti-calendar"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.logs.logs') }}</span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <H3 class="card-title">{{ __('resources.label_geral') }}</H3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-2 @if($errors->has('type')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('messages.person-type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>

                                @if($disabled)
                                <input type='hidden' name="type" value='{{ $provider->type }}' >
                                <select class="form-control"  disabled>
                                @else
                                <select class="form-control" name="type" {{ $disabled }}>
                                @endif
                                    <option value="{{ App\Enums\PersonType::FISICAL }}" @if(old('type', $provider->type) == App\Enums\PersonType::FISICAL) selected @endif>{{ __('messages.person-fisical') }}</option>
                                    <option value="{{ App\Enums\PersonType::LEGAL }}" @if(old('type', $provider->type) == App\Enums\PersonType::LEGAL) selected @endif>{{ __('messages.person-legal') }}</option>
                                </select>
                                


                            </div>
                            <div class="form-group col-md-8 @if($errors->has('legal_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.legal-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="legal_name" value="{{ old('legal_name', $provider->legal_name) }}"  {{ $disabled }}/>
                            </div>
                            <div class="form-group col-md-8 @if($errors->has('name')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="name" value="{{ old('name', $provider->name) }}"  {{ $disabled }}/>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('status')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.status') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="status" required {{ $disabled }}>
                                    <option value="{{ App\Enums\ProcessStatus::ACTIVE }}" @if(old('status', $provider->status) == App\Enums\ProcessStatus::ACTIVE) selected @endif>{{ __('resources.process-statues.active') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::SUSPENDED }}" @if(old('status', $provider->status) == App\Enums\ProcessStatus::SUSPENDED) selected @endif>{{ __('resources.process-statues.suspended') }}</option>
                                    <option value="{{ App\Enums\ProcessStatus::IN_ANALYSIS }}" @if(old('status', $provider->status) == App\Enums\ProcessStatus::IN_ANALYSIS) selected @endif>{{ __('resources.process-statues.in-analysis') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('company_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.company-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="company_name" value="{{ old('company_name', $provider->company_name) }}" {{ $disabled }} >
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('email')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control text-lowercase" name="email" value="{{ old('email', $provider->email) }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('gender')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('messages.gender') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                @if($disabled)
                                <input type='hidden' name="gender" value='{{ $provider->gender }}' >
                                <select class="form-control"  disabled>
                                @else
                                <select class="form-control" name="gender" {{ $disabled }}>
                                @endif
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Gender::MALE }}" @if(old('gender', $provider->gender) == App\Enums\Gender::MALE) selected @endif>{{ __('messages.male') }}</option>
                                    <option value="{{ App\Enums\Gender::FEMALE }}" @if(old('gender', $provider->gender) == App\Enums\Gender::FEMALE) selected @endif>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('birthdate')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.birthdate') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="10" class="form-control datepicker" name="birthdate" value="{{ old('birthdate', $provider->birthdate ? $provider->birthdate->format('d/m/Y') : '') }}" placeholder="__/__/____" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language', $provider->language) == App\Enums\Language::PORTUGUESE) selected @endif>Português</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language', $provider->language) == App\Enums\Language::ENGLISH) selected @endif>Inglês</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language', $provider->language) == App\Enums\Language::SPANISH) selected @endif>Espanhol</option>
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
                                    @include('frontend.template.components.select-country-options', ['selectedValue' => old('address.country', $address->country)])
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.state') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[state]"></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.city') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="address[city]"></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.zip')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.zip') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', $address->zip) }}" onkeypress="handleZipChange">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="address[address]" value="{{ old('address.address', $address->address) }}" >
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number', $address->number) }}" >
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }} <span class="text-danger" data-brazil-only></span></strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood', $address->neighborhood) }}" >
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement', $address->complement) }}">
                            </div>
                        </div>
                        <H3 class="card-title">{{ __('resources.label_contact') }}</H3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('primary_document')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="primary_document" onchange="handlePrimaryDocumentChange()" {{ $disabled }}>
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\DocumentType::IDENTITY }}" @if(old('primary_document', $provider->primary_document) == App\Enums\DocumentType::IDENTITY) selected @endif>{{ __('resources.providers.model.doc-identity') }}</option>
                                    <option value="{{ App\Enums\DocumentType::PASSPORT }}" @if(old('primary_document', $provider->primary_document) == App\Enums\DocumentType::PASSPORT) selected @endif>{{ __('resources.providers.model.doc-passport') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('identity')) has-danger @endif" not-legal data-identity-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-identity') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="identity" value="{{ old('identity', $provider->identity) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('registry')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-registry') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="registry" value="{{ old('registry', $provider->registry) }}" {{ $disabled }}>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('uf')) has-danger @endif" not-legal data-identity-required data-brazil-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.uf') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <select class="form-control" name="uf">
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->iso2 }}" @if(old('uf', $provider->uf) == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('passport')) has-danger @endif" not-legal data-passport-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-passport') }}</strong>
                                    <span class="text-danger" data-passport-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="passport" value="{{ old('passport', $provider->passport) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('responsible_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.responsible-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="responsible_name" value="{{ old('responsible_name', $provider->responsible_name) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('responsible_email')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.responsible-email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="responsible_email" value="{{ old('responsible_email', $provider->responsible_email) }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('document')) has-danger @endif" not-legal data-brazil-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-document') }} <span class="text-danger" data-brazil-only>*</span></strong>
                                </label>
                                <input type="text" class="form-control" name="document" value="{{ old('document', $provider->document) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.newsletter') }} <span class="text-danger">*</span></strong>
                                </label>
                                <select class="form-control @if($errors->has('is_newsletter_subscriber')) has-danger @endif" name="is_newsletter_subscriber">
                                    <option value="1" @if (old('is_newsletter_subscriber', $provider->is_newsletter_subscriber) == 1) selected @endif>{{ __('resources.accesses.subscribed') }}</option>
                                    <option value="0" @if (old('is_newsletter_subscriber', $provider->is_newsletter_subscriber) == 0) selected @endif>{{ __('resources.accesses.no-interest') }}</option>
                                </select>
                            </div>

                        </div>


                        @foreach($provider->contacts as $contact)
                            <input type="hidden" name="contacts[id][]" value="{{ $contact->id }}" />
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.type') }}</strong>
                                    </label>
                                    <select class="form-control" name="contacts[type][]">
                                        <option value>{{ __('messages.select') }}</option>
                                        <option value="{{ App\Enums\ContactType::MOBILE }}" @if($contact->type == App\Enums\ContactType::MOBILE) selected @endif>{{ __('resources.contacts.types.mobile') }}</option>
                                        <option value="{{ App\Enums\ContactType::WHATSAPP }}"  @if($contact->type == App\Enums\ContactType::WHATSAPP) selected @endif>{{ __('resources.contacts.types.whatsapp') }}</option>
                                        <option value="{{ App\Enums\ContactType::RESIDENTIAL }}"  @if($contact->type == App\Enums\ContactType::RESIDENTIAL) selected @endif>{{ __('resources.contacts.types.residential') }}</option>
                                        <option value="{{ App\Enums\ContactType::COMMERCIAL }}"  @if($contact->type == App\Enums\ContactType::COMMERCIAL) selected @endif>{{ __('resources.contacts.types.comercial') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.contact') }}</strong>
                                    </label>
                                    <br />
                                    <input type="text" class="form-control phone-flag" name="contacts[value][]" value="{{ $contact->value }}">
                                </div>
                            </div>

                        @endforeach

                        <H3 class="card-title">{{ __('resources.label_acess') }}</H3>
                        <hr>

                            <div class="row">
                                <div class="form-group col-md-3 @if($errors->has('username')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.accesses.user') }} <span class="text-danger">*</span></strong>
                                    </label>
                                    <input type="text" class="form-control" name="username" value="{{ old('username', $provider->username) }}" autocomplete="off"/>
                                </div>
                                <div class="form-group col-md-3 mb-1 @if($errors->has('password')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.accesses.password') }} <span class="text-danger">*</span></strong>
                                    </label>
                                    <input type="password" class="form-control" name="password" value="" autocomplete="new-password"/>
                                </div>
                                <div class="form-group col-md-3 mb-1 @if($errors->has('password_confirmation')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.accesses.confirm-password') }} <span class="text-danger">*</span></strong>
                                    </label>
                                    <input type="password" class="form-control" name="password_confirmation" autocomplete="nope"/>
                                </div>
                                <div class="col-md-12">
                                    <small>{{ __('resources.accesses.hints.password') }}</small>
                                </div>
                            </div>
                            <div class="row mt-4">

                            </div>

                    </div>
                    <div class="tab-pane" id="companies" role="tabpanel">
                        @php
                            $companies = $provider->companies;
                        @endphp
                        @include('backend.companies.table')
                    </div>
                    <div class="tab-pane" id="logs" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-7">
                                <label>
                                    <strong>{{ __('resources.logs.message') }}</strong>
                                </label>
                                <input type="text" name="log[message]" class="form-control" />
                            </div>
                            <div class="form-group col-md-3">
                                <label>
                                    <strong>{{ __('resources.logs.level') }}</strong>
                                </label>
                                <select name="log[level]" class="form-control">
                                    @if (user()->canDeleteBookingLog())
                                    <option value="1">{{ __('resources.logs.levels.1') }}</option>
                                    <option value="2">{{ __('resources.logs.levels.2') }}</option>
                                    <option value="16">{{ __('resources.logs.levels.16') }}</option>
                                    @endif
                                    <option value="4">{{ __('resources.logs.levels.4') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 mt-30 align-middle">
                                <button type="button" class="btn btn-block btn-sm btn-primary" style="padding: 8px 0px;" id="submitLogButton">
                                    <i class="fa fa-plus-circle"></i>
                                    {{ __('resources.logs.create') }}
                                </button>
                            </div>
                        </div>

                        <table class="table full-color-table full-primary-table hover-table">
                            <thead>
                                <th width="5%"></th>
                                <th width="20%"></th>
                                <th width="100%" class="align-middle text-center">{{__('resources.hist_msg')}}</th>
                            </thead>
                            <tbody>
                                @foreach ($providerLogs as $key => $providerLog)
                                    <tr>
                                        <td class="align-middle text-center">
                                            @if (user()->canDeleteProviderLog())
                                                <div class="form-group mt-5">
                                                    <input type="checkbox" class="deleteLogs" name="deleteLogs[{{$key}}]" value="{{ $providerLog->id }}" style="padding: 10px;" />
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                                {{ $providerLog->createdAtLabel }} <i class="fa fa-clock-o"></i> {{ $providerLog->createdAtTimeLabel }}<br>
                                                {!! $providerLog->originLabel !!}
                                                {!! $providerLog->levelLabel !!}
                                                @if ($providerLog->ip && user()->canSeeIpAddresses())

                                                    {{ $providerLog->ip }}
                                                @endif

                                        </td>
                                        <td width="100%">
                                            <p>{{ $providerLog->message }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-sm btn-danger mt-10" onclick="deleteLogs()">
                            <i class="fa fa-trash"></i> {{ __('resources.excluir_select') }}
                        </button>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.providers.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                    <a href="{{ route('backend.providers.destroy', $provider) }}"  class="btn btn-danger delete pull-right">
                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/intl-tel-input/css/intlTelInput.css">
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    @include('backend.template.scripts.delete-logs', ['deleteLogRouteUrl' => route("backend.providers.deleteLog", $provider)])
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            catchFormSubmit($('#providerForm'));

            fillAddress({
                country: "{{ old('address.country', $provider->country) }}",
                state: "{{ old('address.state', $provider->address->state()->iso2 ?? $provider->address->state()) }}",
                city: "{{ old('address.city', $provider->address->city()->id ?? $provider->address->city()) }}"
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                assumeNearbyYear: true,
                maxViewMode: 2,
            });

            $(function() {
                $('#companiesTable').DataTable().destroy();
            });

            $('#submitLogButton').click(function (e)  {
                e.preventDefault();

                let message = $('input[name="log[message]"]').val();
                let level = $('select[name="log[level]"]').val();
                let csrf = $('input[name="_token"]').val();
                let url = '{{ route("backend.providers.storeLog", $provider) }}';

                let form = $('<form>', {
                    'action': url,
                    'method': 'POST',
                })
                .append(`<input type="hidden" name="log[message]" value="${message}" />`)
                .append(`<input type="hidden" name="log[level]" value="${level}" />`)
                .append(`<input type="hidden" name="_token" value="${csrf}" />`)
                .appendTo(document.body);

                form.submit();
            });
        });
    </script>
@endpush
