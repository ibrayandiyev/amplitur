@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.offers.replicate') }} {{ __('resources.offers.name') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.offers.index') }}">{{ __('resources.offers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $offer->package->name }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.offers.replicate') }}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.offers.basic-info') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong><i class="fa fa-info-circle"></i>{{__('messages.info')}} </strong><br />
                            {!!__('messages.select_company')!!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label">
                        <strong>{{ __('resources.companies.name') }}</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select name="company_id" class="form-control ">
                        <option value="">{{ __('messages.select') }}</option>
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" data-provider-id="{{ $company->provider->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" class="btn btn-primary" id="nextButton">
                    <i class="fa fa-arrow-right"></i> <strong>{{ __('messages.proceed') }}</strong>
                </a>
                <a href="{{ route('backend.offers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('[name="company_id"]').change(function (e) {
            let option = $(this).find(':selected');
            let provider = option.data('provider-id');
            let company = option.val();
            let button = $('#nextButton');
            let offer = '{{ $offer->id }}';

            button.attr('href', `/admin/ofertas/${offer}/replicar?company=${company}&provider=${provider}`);
        });
    });
</script>
@endpush
