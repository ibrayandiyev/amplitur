@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.events.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.events.index') }}">{{ __('resources.events.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.events.replicate') }}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="eventForm" method="post" action="{{ route('backend.events.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.events.info') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#basic-info" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.basic-info') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#seo" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">SEO</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="basic-info" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-6 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.name') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control text-uppercase" name="name" value="{{ old('name', $event->name) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.category') }}</strong>
                                </label>
                                <select class="form-control text-uppercase" name="category_id">
                                    <option value="">{{ __('messages.select') }}</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($category->id == old('category_id', $event->category_id)) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.is_exclusive') }}</strong>
                                </label>
                                <select class="form-control text-uppercase" name="is_exclusive">
                                    <option value="0" @if (old('is_exclusive', $event->is_exclusive) == 0) selected @endif>{{ __('messages.no') }}</option>
                                    <option value="1" @if (old('is_exclusive', $event->is_exclusive) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 @if($errors->has('address.country')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.country') }}</strong>
                                </label>
                                <select class="form-control" name="address[country]" >
                                    <option value>{{ __('messages.select') }}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if(old('address.country', $event->country) == $country->iso2) selected @endif>{{ country($country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.state')) has-danger @endif" onchange="handleStateChange()" data-state-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.state') }}</strong>
                                </label>
                                <select class="form-control" name="address[state]" disabled></select>
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('address.city')) has-danger @endif" data-city-region>
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.city') }}</strong>
                                </label>
                                <select class="form-control" name="address[city]" disabled></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('description')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.description') }}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="seo-description-pt-br" class="tab-pane active">
                                        <textarea class="form-control summernote" name="description[pt-br]" rows="8">{!! old('description.pt-br', $event->getTranslation('description', 'pt-br')) !!}</textarea>
                                    </div>
                                    <div id="seo-description-en" class="tab-pane">
                                        <textarea class="form-control summernote" name="description[en]" rows="8">{!! old('description.en', $event->getTranslation('description', 'en')) !!}</textarea>
                                    </div>
                                    <div id="seo-description-es" class="tab-pane">
                                        <textarea class="form-control summernote" name="description[es]" rows="8">{!! old('description.es', $event->getTranslation('description', 'es')) !!}</textarea>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-b-30 m-t-10">
                                    <li class="nav-item active"><a href="#seo-description-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#seo-description-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#seo-description-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="seo" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('meta_keywords')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.meta-keywords') }}</strong>
                                </label>

                                <div class="tab-content br-n pn">
                                    <div id="seo-meta-keywords-pt-br" class="tab-pane active">
                                        <div class="tags-default">
                                            <input type="text" name="meta_keywords[pt-br]" class="form-control" data-role="tagsinput" value="{{ old('meta_keywords',$event->getTranslation('meta_keywords', 'pt-br')) }}"/>
                                            <span class="help-block">
                                                <small>{{ __('resources.events.hints.keywords') }}</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="seo-meta-keywords-en" class="tab-pane">
                                        <div class="tags-default">
                                            <input type="text" name="meta_keywords[en]" class="form-control" data-role="tagsinput" value="{{ old('meta_keywords',$event->getTranslation('meta_keywords', 'en')) }}"/>
                                            <span class="help-block">
                                                <small>{{ __('resources.events.hints.keywords') }}</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="seo-meta-keywords-es" class="tab-pane">
                                        <div class="tags-default">
                                            <input type="text" name="meta_keywords[es]" class="form-control" data-role="tagsinput" value="{{ old('meta_keywords',$event->getTranslation('meta_keywords', 'es')) }}"/>
                                            <span class="help-block">
                                                <small>{{ __('resources.events.hints.keywords') }}</small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-b-30 m-t-10">
                                    <li class="nav-item active"><a href="#seo-meta-keywords-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#seo-meta-keywords-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#seo-meta-keywords-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 @if($errors->has('meta_description')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.events.model.meta-description') }}</strong>
                                </label>
                                <div class="tab-content br-n pn">
                                    <div id="seo-meta-description-pt-br" class="tab-pane active">
                                        <textarea name="meta_description[pt-br]" class="form-control" rows="5">{{ old('meta_description', $event->getTranslation('meta_description', 'pt-br')) }}</textarea>
                                    </div>
                                    <div id="seo-meta-description-en" class="tab-pane">
                                        <textarea name="meta_description[en]" class="form-control" rows="5">{{ old('meta_description', $event->getTranslation('meta_description', 'en')) }}</textarea>
                                    </div>
                                    <div id="seo-meta-description-es" class="tab-pane">
                                        <textarea name="meta_description[es]" class="form-control" rows="5">{{ old('meta_description', $event->getTranslation('meta_description', 'es')) }}</textarea>
                                    </div>
                                </div>
                                <ul class="nav nav-pills m-b-30 m-t-10">
                                    <li class="nav-item active"><a href="#seo-meta-description-pt-br" class="nav-link active" data-toggle="tab" aria-expanded="false">{{ __('messages.portuguese') }}</a></li>
                                    <li class="nav-item"><a href="#seo-meta-description-en" class="nav-link" data-toggle="tab" aria-expanded="false">{{ __('messages.english') }}</a></li>
                                    <li class="nav-item"><a href="#seo-meta-description-es" class="nav-link" data-toggle="tab" aria-expanded="true">{{ __('messages.spanish') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.events.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                    <a href="{{ route('backend.events.destroy', $event) }}"  token="{{ csrf_token() }}" class="btn btn-danger delete pull-right">
                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
    <link rel="stylesheet" href="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.css"  />
@endpush

@push('scripts')
    <script src="/backend/js/resources/addressable.js"></script>
    <script src="/backend/js/resources/events.resource.js"></script>
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script src="/backend/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript">
        fillAddress({
            country: "{{ old('address.country', $event->country) }}",
            state: "{{ old('address.state', $event->state) }}",
            city: "{{ old('address.city', $event->city) }}"
        });

        $('.summernote').summernote({
            height: 350,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    </script>
@endpush
