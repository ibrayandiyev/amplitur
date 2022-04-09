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


<form id="packageForm" method="post" action="{{ route('backend.financial.decrypt') }}" autocomplete="off">
    @csrf
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
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control text-uppercase" name="booking_id"  value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 ">
                                    <label class="form-control-label">
                                        <strong>{{ __('resources.decryptor.code') }}</strong>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control text-uppercase"
                                    name="code"  value="">
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary save">
                <i class="fa fa-save"></i> {{ __('messages.decrypt') }}
            </button>
            <a href="{{ route('backend.packages.index') }}" class="btn btn-secondary">
                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
            </a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@endpush
