<div class="row">
    <div class="col-md-12">
        <div class="card border-rounded">
            <div class="card-header text-dark">
                {{ __('resources.bookings.model.client') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.type')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.type') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[type]" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value="{{ App\Enums\PersonType::FISICAL }}" @if(old('bookingClient.type', $client->type) == App\Enums\PersonType::FISICAL) selected @endif>{{ __('messages.person-fisical') }}</option>
                            <option value="{{ App\Enums\PersonType::LEGAL }}" @if(old('bookingClient.type', $client->type) == App\Enums\PersonType::LEGAL) selected @endif>{{ __('messages.person-legal') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-7 @if($errors->has('bookingClient.legal_name')) has-danger @endif" not-fisical>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.legal_name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[legal_name]" value="{{ old('bookingClient.legal_name', $client->legal_name) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-7 @if($errors->has('bookingClient.name')) has-danger @endif" not-legal>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[name]" value="{{ old('bookingClient.name', $client->name) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.company_name')) has-danger @endif" not-fisical>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.company_name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[company_name]" value="{{ old('bookingClient.company_name', $client->company_name) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.email')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.email') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control text-lowercase" name="bookingClient[email]" value="{{ old('bookingClient.email', $client->email) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.gender')) has-danger @endif" not-legal>
                        <label class="form-control-label">
                            <strong>{{ __('messages.gender') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[gender]" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            <option value="{{ App\Enums\Gender::MALE }}" @if(old('bookingClient.gender', $client->gender) == App\Enums\Gender::MALE) selected @endif>{{ __('messages.male') }}</option>
                            <option value="{{ App\Enums\Gender::FEMALE }}" @if(old('bookingClient.gender', $client->gender) == App\Enums\Gender::FEMALE) selected @endif>{{ __('messages.female') }}</option>
                            <option value="{{ App\Enums\Gender::OTHER }}" @if(old('bookingClient.gender', $client->gender) == App\Enums\Gender::OTHER) selected @endif>{{ __('messages.other') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.birthdate')) has-danger @endif" not-legal>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.birthdate') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" maxlength="10" class="form-control datepicker" name="bookingClient[birthdate]" value="{{ old('bookingClient.birthdate', $client->birthdate->format('d/m/Y')) }}" placeholder="__/__/____" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.language')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.language.language') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[language]" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('bookingClient.language', $client->language) == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                            <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('bookingClient.language', $client->language) == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                            <option value="{{ App\Enums\Language::SPANISH }}" @if(old('bookingClient.language', $client->language) == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_country')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.country') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[address_country]" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->iso2 }}" @if(old('bookingClient.address_country', $client->address_country) == $country->iso2) selected @endif>{{ country($country) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.state') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[address_state]" disabled></select>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_city')) has-danger @endif" data-city-region>
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.city') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[address_city]" disabled></select>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_zip')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.zip') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[address_zip]" value="{{ old('bookingClient.address_zip', $client->address_zip) }}" onkeypress="handleZipChange" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-5 @if($errors->has('bookingClient.address')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.address') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[address]" value="{{ old('bookingClient.address', $client->address) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-1 @if($errors->has('bookingClient.address_number')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.number') }} <span class="text-danger">*</span></strong>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[address_number]" value="{{ old('bookingClient.address_number', $client->address_number) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_neighborhood')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.neighborhood') }} <span class="text-danger" data-brazil-only>*</span></strong>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[address_neighborhood]" value="{{ old('bookingClient.address_neighborhood', $client->address_neighborhood) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.address_complement')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.complement') }}</strong>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[address_complement]" value="{{ old('bookingClient.address_complement', $client->address_complement) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.primary_document')) has-danger @endif" not-legal>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.primary_document') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="bookingClient[primary_document]" onchange="handlePrimaryDocumentChange()" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            <option value="{{ App\Enums\DocumentType::IDENTITY }}" @if(old('bookingClient.primary_document', $client->primary_document) == App\Enums\DocumentType::IDENTITY) selected @endif>{{ __('resources.clients.model.doc-identity') }}</option>
                            <option value="{{ App\Enums\DocumentType::PASSPORT }}" @if(old('bookingClient.primary_document', $client->primary_document) == App\Enums\DocumentType::PASSPORT) selected @endif>{{ __('resources.clients.model.doc-passport') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.identity')) has-danger @endif" not-legal data-identity-required>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.identity') }}</strong>
                            <span class="text-danger" data-identity-required>*</span>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[identity]" value="{{ old('bookingClient.identity', $client->identity) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.uf')) has-danger @endif" not-legal data-identity-required data-brazil-only>
                        <label class="form-control-label">
                            <strong>{{ __('resources.address.uf') }}</strong>
                            <span class="text-danger" data-identity-required>*</span>
                        </label>
                        <select class="form-control" name="bookingClient[uf]" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->iso2 }}" @if(old('bookingClient.uf', $client->uf) == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.passport')) has-danger @endif" not-legal data-passport-required>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.passport') }}</strong>
                            <span class="text-danger" data-passport-required>*</span>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[passport]" value="{{ old('bookingClient.passport', $client->passport) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('bookingClient.registry')) has-danger @endif" not-fisical>
                        <label class="form-control-label">
                            <strong>{{ __('resources.booking-clients.model.registry') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[registry]" value="{{ old('bookingClient.registry', $client->registry) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('bookingClient.responsible_name')) has-danger @endif" not-fisical>
                        <label class="form-control-label">
                            <strong>{{ __('resources.providers.model.responsible-name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-uppercase" name="bookingClient[responsible_name]" value="{{ old('bookingClient.responsible_name', $client->responsible_name) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-4 @if($errors->has('bookingClient.responsible_email')) has-danger @endif" not-fisical>
                        <label class="form-control-label">
                            <strong>{{ __('resources.providers.model.responsible-email') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control text-lowercase" name="bookingClient[responsible_email]" value="{{ old('bookingClient.responsible_email', $client->responsible_email) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.identity')) has-danger @endif" not-legal data-brazil-only>
                        <label class="form-control-label">
                            <strong>{{ __('resources.label.indentity') }} <span class="text-danger" data-brazil-only>*</span></strong>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[identity]" value="{{ old('bookingClient.identity', $client->identity) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>


                    <div class="form-group col-md-2 @if($errors->has('bookingClient.uf')) has-danger @endif" not-legal data-brazil-only>
                        <label class="form-control-label">
                            <strong>{{ __('resources.label.uf') }} <span class="text-danger" data-brazil-only>*</span></strong>
                        </label>
                        <select class="form-control" name="bookingClient[uf]" value="{{ old('bookingClient.uf', $client->uf) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                            <option value>{{ __('messages.select') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->iso2 }}" @if(old('bookingClient.uf', $client->uf) == $state->iso2) selected @endif>{{ $state->iso2 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('bookingClient.document')) has-danger @endif" not-legal data-brazil-only>
                        <label class="form-control-label">
                            <strong>{{ __('resources.label.cpf') }} <span class="text-danger" data-brazil-only>*</span></strong>
                        </label>
                        <input type="text" class="form-control" name="bookingClient[document]" value="{{ old('bookingClient.document', $client->document) }}" @if ($booking->isCanceled()) disabled readonly @endif>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
