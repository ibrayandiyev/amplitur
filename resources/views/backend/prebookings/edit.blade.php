@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.prebookings.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.prebookings.index') }}">
                    {{ __('resources.prebookings.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.prebookings.edit') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="prebookingForm" method="post" action="{{ route('backend.prebookings.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.prebookings.name-plural') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-8 @if($errors->has('event_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.events.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <p class="text-uppercase">
                                <a href="{{ route('backend.events.edit', $prebooking->event_id) }}">
                                    {{ $prebooking->event->name }}
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </p>
                        </div>
                        <div class="form-group col-md-4  @if($errors->has('country')) has-danger @endif">
                            <label>{{ __('resources.address.country') }}</label>
                            <select name="country" class="form-control">
                                <option value>{{ __('messages.select') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if (old('country', $prebooking->country) == $country->iso2) selected @endif>{{ country($country->iso2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 @if($errors->has('client_id')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.clients.name') }}</strong>
                            </label>
                            @if ($prebooking->isFromClient())
                                <p class="text-uppercase">
                                    <a href="{{ route('backend.clients.edit', $prebooking->client_id) }}">
                                        {{ $prebooking->getName() }}
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                </p>
                            @else
                                <select class="form-control" name="client_id">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" @if ($client->id == $prebooking->client_id) selected @endif>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="form-group col-md-12 @if($errors->has('name')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.prebookings.model.name') }}</strong>
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $prebooking->name) }}" />
                        </div>
                        <div class="form-group col-md-4 @if($errors->has('email')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.prebookings.model.email') }}</strong>
                            </label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', $prebooking->email) }}" />
                        </div>
                        <div class="form-group col-md-4 @if($errors->has('phone')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.prebookings.model.phone') }}</strong>
                            </label>
                            <input type="text" class="form-control phone-flag" name="phone" value="{{ old('phone', $prebooking->phone) }}" />
                        </div>
                        <div class="form-group col-md-4 @if($errors->has('phone')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.prebookings.model.responsible') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="responsible">
                                <option value="{{ \App\Enums\ResponsibleType::CLIENT }}" @if (old('responsible', $prebooking->responsible) == \App\Enums\ResponsibleType::CLIENT ) selected @endif>{{ __('resources.prebookings.model.responsibles.client') }}</option>
                                <option value="{{ \App\Enums\ResponsibleType::AGENCY }}" @if (old('responsible', $prebooking->responsible) == \App\Enums\ResponsibleType::AGENCY ) selected @endif>{{ __('resources.prebookings.model.responsibles.agency') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.prebookings.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush


@push('scripts')
    @include('backend.template.scripts.select-events')
    <script src="/backend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/backend/js/resources/personable.js"></script>
@endpush
