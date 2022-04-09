@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.additionals.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('backend.additionals.index') }}">{{ __('resources.additionals.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $additional->name }}</li>
        </ol>
    </div>
    <div class="col-md-4">
        <div class="float-right">
            <a href="{{ route('backend.additionals.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.additionals.create') }}
            </a>
            <a href="{{ route('backend.additionals.groups.index') }}" class="btn btn-secondary">
                <i class="fa fa-list"></i>
                {{ __('resources.additionals.groups.index') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="additionalsForm" method="post" action="{{ route('backend.additionals.update', $additional) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.additionals.info') }}
                </div>

                <div class="card-body row">
                    <div class="form-group col-md-8">
                        <label class="form-control-label">
                            <strong>{{ __('resources.packages.name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="package_id" class="form-control select2 m-b-10" style="width: 100%">
                            <option value disabled>{{ __('messages.select') }}</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}" @if($additional->package_id == $package->id) selected @endif>{{ $package->extendedName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-control-label">
                            <strong>{{ __('resources.offers.type') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="type">
                            <option value="{{ \App\Enums\OfferType::ALL }}" @if (old('type', $additional->type) == \App\Enums\OfferType::ALL) selected @endif>{{ __('resources.offers.model.types.all') }}</option>
                            <option value="{{ \App\Enums\OfferType::BUSTRIP }}" @if (old('type', $additional->type) == \App\Enums\OfferType::BUSTRIP) selected @endif>{{ __('resources.offers.model.types.bus-trip') }}</option>
                            <option value="{{ \App\Enums\OfferType::HOTEL }}" @if (old('type', $additional->type) == \App\Enums\OfferType::HOTEL) selected @endif>{{ __('resources.offers.model.types.hotel') }}</option>
                            <option value="{{ \App\Enums\OfferType::SHUTTLE }}" @if (old('type', $additional->type) == \App\Enums\OfferType::SHUTTLE) selected @endif>{{ __('resources.offers.model.types.shuttle') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('resources.text_portuguese') }})</strong>
                        </label>
                        <input type="text" name="name[pt-br]" class="form-control " value="{{ old('name.pt-br', $additional->getTranslation('name', 'pt-br')) }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('resources.text_english') }})</strong>
                        </label>
                        <input type="text" name="name[en]" class="form-control " value="{{ old('name.en', $additional->getTranslation('name', 'en')) }}" required/>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('resources.text_spanish') }})</strong>
                        </label>
                        <input type="text" name="name[es]" class="form-control " value="{{ old('name.es', $additional->getTranslation('name', 'es')) }}" required/>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.groups.name') }}</strong>
                        </label>
                        <select class="form-control " name="additional_group_id">
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if (old('additional_group_id', $additional->additional_group_id) == $group->id) selected @endif>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2  @if($errors->has('currency')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.currency') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="currency">
                            <option value>{{ __('messages.select') }}</option>
                            <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency', $additional->currency) == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                            <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency', $additional->currency) == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                            <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency', $additional->currency) == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                            <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency', $additional->currency) == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('price')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.sale_price') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control input-money" name="price" value="{{ old('price', money($additional->price)) }}" />
                    </div>
                    <div class="form-group col-md-2 @if($errors->has('stock')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.additionals.model.stock') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="stock" value="{{ old('stock', $additional->getStock()) }}" />
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.additionals.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
