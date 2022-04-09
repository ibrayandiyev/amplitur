@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.users.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.configs.users.index') }}">{{ __('resources.users.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.users.edit') }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.configs.users.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.users.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="userForm" method="post" action="{{ route('backend.configs.users.update', $user) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put" />
                <div class="labelx label-service">
                    {{ __('resources.users.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-6 @if($errors->has('full_name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.users.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $user->full_name) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.label.nickname') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.language.language') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" name="language">
                                    <option value>{{ __('messages.select') }}</option>
                                    <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language', $user->language) == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                    <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language', $user->language) == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                    <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language', $user->language) == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 @if($errors->has('email')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.users.model.email') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control text-lowercase" name="email" value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('username')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.users.model.username') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="username" value="{{ old('username', $user->username) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.accesses.status') }} <span class="text-danger">*</span></strong>
                                </label>
                                <select class="form-control @if($errors->has('status')) has-danger @endif" name="status">
                                    @foreach (App\Enums\AccessStatus::toArray() as $status)
                                        <option value="{{ $status }}" @if (old('status', $user->status) == $status) selected @endif>{{ __('resources.access-status.' . $status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
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
                            <div class="col-md-12">
                                <small>{{ __('resources.accesses.hints.password') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.configs.users.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
