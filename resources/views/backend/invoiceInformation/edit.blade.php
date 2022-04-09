@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.invoiceInformation.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.invoiceInformation.index', []) }}">{{ __('resources.invoiceInformation.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $invoiceInformation->id }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.invoiceInformation.create', []) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.invoiceInformation.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="clientForm" method="post" action="{{ route('backend.invoiceInformation.update', ['invoice_information' => $invoiceInformation]) }}" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" value="put">
                <div class="labelx label-service">
                    {{ __('resources.invoiceInformation.info') }}
                </div>
                <div class="form-group col-md-4 @if($errors->has('provider_id')) has-danger @endif">
                        <label class="form-control-label">
                            <strong>{{ __('resources.invoiceInformation.currency') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="currency_id" class="form-control text-uppercase">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" @if (old('currency_id') == $currency->id || $currency->id == $invoiceInformation->currency_id)  selected="selected"  @endif>{{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                <div class="card-body row">
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('resources.invoiceInformation.model.name') }} ({{ __('messages.portuguese') }})</label>
                        <span class="text-danger">*</span>
                        <textarea name="description" class="form-control summernote">{{ old('description', $invoiceInformation->description ) }}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.invoiceInformation.index', []) }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
    <link href="/backend/vendors/summernote/dist/summernote.css" rel="stylesheet" />
    <link rel="stylesheet" href="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"  />
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 250,
            });

        });
    </script>
@endpush
