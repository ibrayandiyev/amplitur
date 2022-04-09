@extends('backend.template.default')

@php
    $queryString = '?';
    $queryString .= !empty($offerType) ? '&offerType=' . $offerType : '';
    $queryString .= !empty($provider) ? '&provider_id=' . $provider->id : '';
    $queryString .= !empty($event) ? '&event_id=' . $event->id : '';
    $queryString .= !empty($company) ? '&company_id=' . $company->id : '';
@endphp

@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.packages.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.packages.index') }}">
                    {{ __('resources.packages.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.packages.create') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.packages.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                 {{ __('resources.packages.create') }}
            </a>
        </div>
    </div>
</div>


<form id="packageForm" method="post" action="{{ route('backend.packages.store') }}{{$queryString}}" autocomplete="off">
    @csrf
    <div class="card">
        <div class="labelx label-service">
            {{ __('resources.bus-trip.info') }}
        </div>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('resources.bus-trip.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tab-panel">
                        <div class="row">
                            @if (!empty($event))
                                <div class="form-group col-md-9 @if($errors->has('event_id')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.name') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="text" class="form-control " name="event" value="{{ $event->name }}" readonly>
                                </div>

                                @if (user()->canManagePackageDetails())
                                    <div class="form-group col-md-3 @if($errors->has('display_type')) has-danger @endif">
                                        <label class="form-control-label">
                                            <strong>{{ __('resources.events.model.display_type') }}</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" name="display_type">
                                            <option value="{{ \App\Enums\DisplayType::PUBLIC }}" @if (old('display_type') == \App\Enums\DisplayType::PUBLIC) selected @endif>{{ __('resources.events.model.display_types.public') }}</option>
                                            <option value="{{ \App\Enums\DisplayType::NON_LISTED }}" @if (old('display_type') == \App\Enums\DisplayType::NON_LISTED) selected @endif>{{ __('resources.events.model.display_types.non_listed') }}</option>
                                        </select>
                                    </div>
                                @endif
                            @else
                                <div class="form-group col-md-9 @if($errors->has('event_id')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.events.name') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="event_id" class="form-control">
                                        @if(old("event_id"))
                                            @php
                                                $event = \App\Models\Event::find(old("event_id"));
                                                $eventName = $event->name;
                                            @endphp
                                        <option value="{{old("event_id")}}" selected="selected">{{$eventName}}</option>
                                        @endif
                                    </select>
                                </div>

                                @if (user()->canManagePackageDetails())
                                    <div class="form-group col-md-3 @if($errors->has('display_type')) has-danger @endif">
                                        <label class="form-control-label">
                                            <strong>{{ __('resources.events.model.display_type') }}</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" name="display_type">
                                            <option value="{{ \App\Enums\DisplayType::PUBLIC }}" @if (old('display_type') == \App\Enums\DisplayType::PUBLIC) selected @endif>{{ __('resources.events.model.display_types.public') }}</option>
                                            <option value="{{ \App\Enums\DisplayType::NON_LISTED }}" @if (old('display_type') == \App\Enums\DisplayType::NON_LISTED) selected @endif>{{ __('resources.events.model.display_types.non_listed') }}</option>
                                        </select>
                                    </div>
                                @endif
                            @endif
                        </div>
                        @if (auth()->user()->isMaster())
                            <div class="row">
                                <div class="form-group col-md-12 @if($errors->has('provider_id')) has-danger @endif">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.providers.name') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="provider_id" class="form-control  select2">
                                        <option value="">{{ __('messages.select') }}</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}" @if (old('provider_id') == $provider->id) selected @endif>{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('starts_at')) has-danger @endif" data-one-day-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.starts_at') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" maxlength="17" class="form-control datetimepicker" name="starts_at" value="{{ old('starts_at') }}" placeholder="__/__/____, 00:00">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('ends_at')) has-danger @endif" data-range-date-only>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.ends_at') }}</strong>
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" maxlength="17" class="form-control datetimepicker" name="ends_at" value="{{ old('ends_at') }}" placeholder="__/__/____, 00:00">
                            </div>
                        </div>
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
                                    <strong>{{ __('resources.address.number') }} </strong>
                                </label>
                                <input type="text" class="form-control " name="address[number]" value="{{ old('address.number') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.neighborhood')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.neighborhood') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[neighborhood]" value="{{ old('address.neighborhood') }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.complement')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.address.complement') }}</strong>
                                </label>
                                <input type="text" class="form-control " name="address[complement]" value="{{ old('address.complement') }}">
                            </div>
                            <div class="form-group col-md-8 @if($errors->has('location')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.location') }}</strong>
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="url" class="form-control " name="location" data-map-location  value="{{ old('location') }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('website')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.bus-trip.model.website') }}</strong>
                                </label>
                                <input type="url" class="form-control text-lowercase" name="website" value="{{ old('website') }}">
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude') }}" />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude') }}" />
                            <div class="col-md-12 map-container" id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary save">
                <i class="fa fa-save"></i> {{ __('messages.save') }}
            </button>
            <a href="{{ route('backend.packages.index') }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>
        </div>
    </div>
</form>
@endsection

@push('metas')
    <meta name="google-maps-key" content="{{ env('GOOGLE_MAPS_KEY') }}">
@endpush

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/mapable.js"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'd/m/Y, H:i',
            mask: true
        });
    </script>
    <script type="text/javascript">
        function handleEventDuration(duration) {
            let oneDayInput = $('[data-one-day-only]');
            let rangeDateInput = $('[data-range-date-only]');
            if (duration == 'one-day') {
                oneDayInput.show();
                rangeDateInput.val('');
                rangeDateInput.hide();
            } else {
                oneDayInput.show();
                rangeDateInput.show();
            }
        }
        $(document).ready(function () {
            fillAddress({
                country: "{{ old('address.country') }}",
                state: "{{ old('address.state') }}",
                city: "{{ old('address.city') }}"
            });

            let inputSelectEvent = $('select[name="event_id"]');

            if (inputSelectEvent != undefined) {
                inputSelectEvent.change(function (e) {
                    let eventId = $(this).val() || 0;
                    let eventDuration = $(this).find('option[value='+ eventId +']').data('duration');

                    handleEventDuration(eventDuration);
                });
            }

            handleEventDuration("{{ $event ? $event->getDuration() : null ?? '' }}");
        });
    </script>
    @if(old("event_id"))
        <script>
            var eventData = [{
                id: "{{$event->id}}",
                name: "{{$event->name}}"
            }];
            @if($event->hasOneDayDuration())
                handleEventDuration("{{$event->getDuration()}}");
            @endif
        </script>
    @endif
    @include('backend.template.scripts.select-events')
@endpush
