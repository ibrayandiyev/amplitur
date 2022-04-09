@extends('backend.template.default')


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
                    {{ __('resources.financial.decryptor') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.financial.decryptor') }}</li>
        </ol>
    </div>

</div>

<div class="card">
    <div class="row">
        <div class="col-12">
            <div class="tab-content p-4">
                <div class="tab-pane active" id="basic-info" role="tab-panel">
                    @if (auth()->user()->isMaster())
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.decryptor.booking_id') }}</strong>
                                    : {{$booking_id}}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.decryptor.holder') }}</strong>
                                    : {{$holder}}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.decryptor.number') }}</strong>
                                    : {{$number}}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.decryptor.expirationDate') }}</strong>
                                    : {{$expirationDate}}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.decryptor.cvv') }}</strong>
                                    : {{$cvv}}
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">

        <a href="{{ route('backend.financial.decryptor') }}" class="btn btn-secondary">
            <i class="fa fa-times-circle-o"></i> {{ __('messages.back') }}
        </a>
    </div>
</div>
@endsection
