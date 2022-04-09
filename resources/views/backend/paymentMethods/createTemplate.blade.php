@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.payment-methods.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.paymentMethods.index') }}">{{ __('resources.payment-methods.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resource.payment-methods.add-new')}}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="paymentMethodForm" method="post" action="{{ route('backend.paymentMethods.storeTemplate') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.payment-methods.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">
                                <strong>Payment Method</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="payment_method_id">
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{$paymentMethod->id}}" @if (old('payment_method', $paymentMethod->id) == $paymentMethod->id) selected @endif>{{ucfirst($paymentMethod->category)}} - {{$paymentMethod->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.paymentMethods.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
