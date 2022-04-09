@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.providers.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.index') }}">{{ __('resources.providers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.providers.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.providers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.providers.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="providerForm" method="post" action="{{ route('backend.providers.store') }}" autocomplete="off">
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
                                <select class="form-control" name="type">
                                    <option value="{{ App\Enums\PersonType::FISICAL }}" @if(old('type') == App\Enums\PersonType::FISICAL) selected @endif>{{ __('messages.person-fisical') }}</option>
                                    <option value="{{ App\Enums\PersonType::LEGAL }}" @if(old('type') == App\Enums\PersonType::LEGAL) selected @endif>{{ __('messages.person-legal') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-8 @if($errors->has('legal_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.legal-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="legal_name" value="{{ old('legal_name') }}">
                            </div>
                            <div class="form-group col-md-8 @if($errors->has('name')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="name" value="{{ old('name') }}">
                            </div>
                            @if(user()->canManageProviders())
                            <div class="form-group col-md-2">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.status') }} <span class="text-danger">*</span></strong>
                                </label>
                                <select class="form-control @if($errors->has('is_active')) has-danger @endif" name="is_active">
                                    <option value="1" @if (old('is_active') == 1) selected @endif>{{ __('resources.accesses.active') }}</option>
                                    <option value="0" @if (old('is_active') == 0) selected @endif>{{ __('resources.accesses.inactive') }}</option>
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('company_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.company-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="company_name" value="{{ old('company_name') }}">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('email')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control text-lowercase" name="email" value="{{ old('email') }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('gender')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('messages.gender') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="gender">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Gender::MALE }}" @if(old('gender') == App\Enums\Gender::MALE) selected @endif>{{ __('messages.male') }}</option>
                                    <option value="{{ App\Enums\Gender::FEMALE }}" @if(old('gender') == App\Enums\Gender::FEMALE) selected @endif>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('birthdate')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.birthdate') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="10" class="form-control datepicker" name="birthdate" value="{{ old('birthdate') }}" placeholder="__/__/____">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language') == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language') == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language') == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
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
                                        @include('frontend.template.components.select-country-options', ['selectedValue' => old('address.country')])
                                    </select>
                                </div>
                                <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
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
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip') }}" onkeypress="handleZipChange">
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
                            <div class="form-group col-md-3 @if($errors->has('primary_document')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="primary_document" onchange="handlePrimaryDocumentChange()">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\DocumentType::IDENTITY }}" @if(old('primary_document') == App\Enums\DocumentType::IDENTITY) selected @endif>{{ __('resources.providers.model.doc-identity') }}</option>
                                    <option value="{{ App\Enums\DocumentType::PASSPORT }}" @if(old('primary_document') == App\Enums\DocumentType::PASSPORT) selected @endif>{{ __('resources.providers.model.doc-passport') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('identity')) has-danger @endif" not-legal data-identity-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-identity') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="identity" value="{{ old('identity') }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('registry')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-registry') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="registry" value="{{ old('registry') }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('uf')) has-danger @endif" not-legal data-identity-required-brazil data-brazil-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.uf') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <select class="form-control" name="uf">
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->iso2 }}" @if(old('uf') == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('passport')) has-danger @endif" not-legal data-passport-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.doc-passport') }}</strong>
                                    <span class="text-danger" data-passport-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="passport" value="{{ old('passport') }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('responsible_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.responsible-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control " name="responsible_name" value="{{ old('responsible_name') }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('responsible_email')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.providers.model.responsible-email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="responsible_email" value="{{ old('responsible_email') }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('document')) has-danger @endif" not-legal data-brazil-only>
                                <label class="form-control-label">
                                    <strong>CPF <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control" name="document" value="{{ old('document') }}">
                            </div>
                        </div>
                        <div class="row">
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.type') }}</strong>
                                    </label>

                                    <select class="form-control" name="contacts[type][0]">
                                        <option value>{{ __('messages.select') }}</option>
                                        <option value="{{ App\Enums\ContactType::MOBILE }}" @if(old('contacts.type.0') == App\Enums\ContactType::MOBILE) selected="selected" @endif>{{ __('resources.contacts.types.mobile') }}</option>
                                        <option value="{{ App\Enums\ContactType::WHATSAPP }}" @if(old('contacts.type.0') == App\Enums\ContactType::WHATSAPP) selected="selected" @endif>{{ __('resources.contacts.types.whatsapp') }}</option>
                                        <option value="{{ App\Enums\ContactType::RESIDENTIAL }}" @if(old('contacts.type.0') == App\Enums\ContactType::RESIDENTIAL) selected="selected" @endif>{{ __('resources.contacts.types.residential') }}</option>
                                        <option value="{{ App\Enums\ContactType::COMMERCIAL }}" @if(old('contacts.type.0') == App\Enums\ContactType::COMMERCIAL) selected="selected" @endif>{{ __('resources.contacts.types.comercial') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.contact') }}</strong>
                                    </label>
                                    <br />
                                    <input type="tel" class="form-control contact-flag phone-flag" name="contacts[value][0]" value="{{ old('contacts.value.0') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.type') }}</strong>
                                    </label>
                                    <select class="form-control" name="contacts[type][1]">
                                        <option value>{{ __('messages.select') }}</option>
                                        <option value="{{ App\Enums\ContactType::MOBILE }}" @if(old('contacts.type.1') == App\Enums\ContactType::MOBILE) selected="selected" @endif>{{ __('resources.contacts.types.mobile') }}</option>
                                        <option value="{{ App\Enums\ContactType::WHATSAPP }}" @if(old('contacts.type.1') == App\Enums\ContactType::WHATSAPP) selected="selected" @endif>{{ __('resources.contacts.types.whatsapp') }}</option>
                                        <option value="{{ App\Enums\ContactType::RESIDENTIAL }}" @if(old('contacts.type.1') == App\Enums\ContactType::RESIDENTIAL) selected="selected" @endif>{{ __('resources.contacts.types.residential') }}</option>
                                        <option value="{{ App\Enums\ContactType::COMMERCIAL }}" @if(old('contacts.type.1') == App\Enums\ContactType::COMMERCIAL) selected="selected" @endif>{{ __('resources.contacts.types.comercial') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.contacts.contact') }}</strong>
                                    </label>
                                    <br />
                                    <input type="tel" class="form-control contact-flag phone-flag" data-initial-flag="br" name="contacts[value][1]" value="{{ old('contacts.value.1') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.accesses.newsletter') }} <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-control @if($errors->has('is_newsletter_subscriber')) has-danger @endif" name="is_newsletter_subscriber">
                                        <option value="1" @if (old('is_newsletter_subscriber') == 1) selected @endif>{{ __('resources.accesses.subscribed') }}</option>
                                        <option value="0" @if (old('is_newsletter_subscriber') == 0) selected @endif>{{ __('resources.accesses.no-interest') }}</option>
                                    </select>
                                </div>
                        </div>

                        <H3 class="card-title">{{ __('resources.label_acess') }}</H3>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('username')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.user') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('password')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.password') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('password_confirmation')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.confirm-password') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="password" class="form-control" name="password_confirmation" />
                            </div>
                        </div>
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

@push('styles')
    <link href="/backend/vendors/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/backend/vendors/dropzone-master/dist/dropzone.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            catchFormSubmit($('#providerForm'));

            fillAddress({
                country: "{{ old('address.country') }}",
                state: "{{ old('address.state') }}",
                city: "{{ old('address.city') }}"
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                assumeNearbyYear: true,
                maxViewMode: 2,
            });
        });
    </script>
@endpush
