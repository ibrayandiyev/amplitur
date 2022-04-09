@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.clients.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.clients.index') }}">{{ __('resources.clients.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">
                @if ($client->type == App\Enums\PersonType::FISICAL)
                {{ $client->name }}
                @else
                {{ $client->company_name }}
                @endif
            </li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.clients.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.clients.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="clientForm" method="post" action="{{ route('backend.clients.update', $client) }}" autocomplete="off">
                <input type="hidden" name="_method" value="put" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.clients.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.clients.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#bookings" role="tab">
                            <span class="hidden-sm-up"><i class="ti-ticket"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bookings.name-plural') }}</span>
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
                                    <strong>{{ __('resources.clients.model.type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="type">
                                    <option value="{{ App\Enums\PersonType::FISICAL }}" @if(old('type', $client->type) == App\Enums\PersonType::FISICAL) selected @endif>{{ __('messages.person-fisical') }}</option>
                                    <option value="{{ App\Enums\PersonType::LEGAL }}" @if(old('type', $client->type) == App\Enums\PersonType::LEGAL) selected @endif>{{ __('messages.person-legal') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-7 @if($errors->has('legal_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.legal-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="legal_name" value="{{ old('legal_name', $client->legal_name) }}">
                            </div>
                            <div class="form-group col-md-7 @if($errors->has('name')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="name" value="{{ old('name', $client->name) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.status') }} <span class="text-danger">*</span></strong>
                                </label>
                                <select class="form-control @if($errors->has('is_active')) has-danger @endif" name="is_active">
                                    <option value="1" @if (old('is_active', $client->is_active) == 1) selected @endif>{{ __('resources.accesses.active') }}</option>
                                    <option value="0" @if (old('is_active', $client->is_active) == 0) selected @endif>{{ __('resources.accesses.inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('company_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.company-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="company_name" value="{{ old('company_name', $client->company_name) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('email')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control text-lowercase" name="email" value="{{ old('email', $client->email) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('gender')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('messages.gender') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="gender">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Gender::MALE }}" @if(old('gender', $client->gender) == App\Enums\Gender::MALE) selected @endif>{{ __('messages.male') }}</option>
                                    <option value="{{ App\Enums\Gender::FEMALE }}" @if(old('gender', $client->gender) == App\Enums\Gender::FEMALE) selected @endif>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('birthdate')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.birthdate') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="10" class="form-control datepicker" name="birthdate" value="{{ old('birthdate', $client->birthdate ? $client->birthdate->format('d/m/Y') : '') }}" placeholder="__/__/____">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language', $client->language) == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language', $client->language) == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language', $client->language) == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
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
                                <input type="text" class="form-control" name="address[zip]" value="{{ old('address.zip', $address->zip) }}" onkeypress="handleZipChange">
                            </div>
                            <div class="form-group col-md-5 @if($errors->has('address.address')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.address') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="address[address]" value="{{ old('address.address', $address->address) }}">
                            </div>
                            <div class="form-group col-md-1 @if($errors->has('address.number')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.number') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="address[number]" value="{{ old('address.number', $address->number) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }} <span class="text-danger" data-brazil-only>*</span></strong>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="address[neighborhood]" value="{{ old('address.neighborhood', $address->neighborhood) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="address[complement]" value="{{ old('address.complement', $address->complement) }}">
                            </div>
                        </div>

                        <H3 class="card-title">{{ __('resources.label_contact') }}</H3>
                        <hr>

                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('primary_document')) has-danger @endif" not-legal>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.doc-type') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="primary_document" data-edit="1" onchange="handlePrimaryDocumentChange()">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\DocumentType::IDENTITY }}" @if(old('primary_document', $client->primary_document) == App\Enums\DocumentType::IDENTITY) selected @endif>{{ __('resources.clients.model.doc-identity') }}</option>
                                    <option value="{{ App\Enums\DocumentType::PASSPORT }}" @if(old('primary_document', $client->primary_document) == App\Enums\DocumentType::PASSPORT) selected @endif>{{ __('resources.clients.model.doc-passport') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('identity')) has-danger @endif" not-legal data-identity-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.doc-identity') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="identity" value="{{ old('identity', $client->identity) }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('uf')) has-danger @endif" not-legal data-identity-required data-brazil-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.uf') }}</strong>
                                    <span class="text-danger" data-identity-required>*</span>
                                </label>
                                <select class="form-control" name="uf">
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->iso2 }}" @if(old('uf', $client->uf) == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('passport')) has-danger @endif" not-legal data-passport-required>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.doc-passport') }}</strong>
                                    <span class="text-danger" data-passport-required>*</span>
                                </label>
                                <input type="text" class="form-control" name="passport" value="{{ old('passport', $client->passport) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('registry')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.doc-registry') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="registry" value="{{ old('registry', $client->registry) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('responsible_name')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.responsible-name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="responsible_name" value="{{ old('responsible_name', $client->responsible_name) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('responsible_email')) has-danger @endif" not-fisical>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.responsible-email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="responsible_email" value="{{ old('responsible_email', $client->responsible_email) }}">
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('document')) has-danger @endif" not-legal data-brazil-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.clients.model.doc-document') }} <span class="text-danger" data-brazil-only>*</span></strong>
                                </label>
                                <input type="text" class="form-control" name="document" value="{{ old('document', $client->document) }}">
                            </div>
                        </div>

                        @foreach($client->contacts as $contact)
                            <input type="hidden" name="contacts[id][]" value="{{ $contact->id }}" />
                            <div class="row">
                                <div class="form-group col-md-3">
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
                                <input type="text" class="form-control text-lowercase" name="username" value="{{ old('username', $client->username) }}" />
                            </div>
                            <div class="form-group col-md-3 mb-1 @if($errors->has('password')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.password') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="password" class="form-control text-lowercase" name="password" />
                            </div>
                            <div class="form-group col-md-3 mb-1 @if($errors->has('password_confirmation')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.confirm-password') }} <span class="text-danger">*</span></strong>
                                </label>
                                <input type="password" class="form-control text-lowercase" name="password_confirmation" />
                            </div>
                            <div class="form-group col-md-1">
                                <label class="form-control-label text-center">
                                    <strong>{{ __("resources.label.logar") }}</strong>
                                </label>
                                    <div class="skip">
                                        <a class="btn btn-warning" href="{{ route('backend.clients.loginAsCustomer', $client->id) }}" target='_blank' data-toggle="tooltip" data-placement="top" title="{{ __('messages.login_as_customer') }}">
                                            <i class="mdi mdi-account-key text-white"></i>
                                        </a>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <small>{{ __('resources.accesses.hints.password') }}</small>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.newsletter') }} <span class="text-danger">*</span></strong>
                                </label>
                                <select class="form-control @if($errors->has('is_newsletter_subscriber')) has-danger @endif" name="is_newsletter_subscriber">
                                    <option value="1" @if (old('is_newsletter_subscriber', $client->is_newsletter_subscriber) == 1) selected @endif>{{ __('resources.accesses.subscribed') }}</option>
                                    <option value="0" @if (old('is_newsletter_subscriber', $client->is_newsletter_subscriber) == 0) selected @endif>{{ __('resources.accesses.no-interest') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="bookings" role="tabpanel">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center"scope="col">#</th>
                                    <th scope="col">{{ __('resources.bookings.model.offer') }}</th>
                                    <th class="text-center" scope="col">{{ __('resources.bookings.model.created_at') }}</th>
                                    <th class="text-center" scope="col">{{ __('resources.bookings.model.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->bookings as $booking)
                                    <tr>
                                        <td class="text-center label-booking align-middle" scope="row"><a href="{{ route('backend.bookings.edit', $booking) }}">{{ $booking->id }}</a></td>
                                        <td class="text-uppercase align-middle">
                                            <span class="label label-light-inverse">{{ $booking->package->extendedNameDate }}</span> <br />
                                            <span class="label label-light-info">{{ $booking->getProductName() }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $booking->createdAtLabel }}</td>
                                        <td class="text-center align-middle">{!! $booking->statusLabel !!}</td>
                                    </tr>
                                @endforeach
                                @foreach ($client->bookingLegacies->sortByDesc("id") as $booking)
                                    <tr>
                                        <td class="text-center label-booking align-middle" scope="row"><a href="#">{{ $booking->booking_id }}</a></td>
                                        <td class="text-uppercase align-middle">
                                            <span class="label label-light-inverse">{{ $booking->name }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $booking->startsAtLabel }}</td>
                                        <td class="text-center align-middle">{!! $booking->statusLabel !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

                        <table with="100%" class="table full-color-table full-primary-table hover-table">
                            <thead>
                                <th width="5%"></th>
                                <th width="20%"></th>
                                <th class="align-middle text-center">{{__('resources.hist_msg')}}</th>
                            </thead>
                            <tbody>
                                @foreach ($clientLogs as $key => $clientLog)
                                    <tr>
                                        <td class="align-middle text-center">
                                            @if (user()->canDeleteClientLog())
                                                <div class="form-group mt-5">
                                                    <input type="checkbox" class="deleteLogs" name="deleteLogs[{{$key}}]" value="{{ $clientLog->id }}" style="padding: 10px;" />
                                                </div>
                                            @endif
                                        </td>
                                        <td>

                                                {{ $clientLog->createdAtLabel }} <i class="fa fa-clock-o"></i> {{ $clientLog->createdAtTimeLabel }}<BR>
                                                {!! $clientLog->originLabel !!}
                                                {!! $clientLog->levelLabel !!}
                                                @if ($clientLog->ip && user()->canSeeIpAddresses())

                                                    {{ $clientLog->ip }}
                                                @endif

                                        </td>
                                        <td>
                                            <p>{{ $clientLog->message }}</p>
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
                    <a href="{{ route('backend.clients.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                    <a href="{{ route('backend.clients.destroy', $client) }}"  class="btn btn-danger delete pull-right">
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
    @include('backend.template.scripts.delete-logs', ['deleteLogRouteUrl' => route("backend.clients.deleteLog", $client)])
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            catchFormSubmit($('#clientForm'));

            fillAddress({
                country: "{{ old('address.country', $client->country) }}",
                state: "{{ old('address.state', $client->address->state()->iso2 ?? $client->address->state()) }}",
                city: "{{ old('address.city', $client->address->city()->id ?? $client->address->city()) }}"
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                clearBtn: true,
                todayHighlight: true,
                assumeNearbyYear: true,
                maxViewMode: 2,
            });

            $('#submitLogButton').click(function (e)  {
                e.preventDefault();

                let message = $('input[name="log[message]"]').val();
                let level = $('select[name="log[level]"]').val();
                let csrf = $('input[name="_token"]').val();
                let url = '{{ route("backend.clients.storeLog", $client) }}';

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
