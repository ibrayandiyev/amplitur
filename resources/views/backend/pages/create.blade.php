@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-12">
        <h3 class="text-themecolor">{{ __('resources.pages.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.pages.index') }}">
                    {{ __('resources.pages.name-plural') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.pages.create') }}</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="pageForm" method="post" action="{{ route('backend.pages.store') }}" autocomplete="off">
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.pages.name-plural') }}
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#pt-br" role="tab">
                            <span class="hidden-sm-up"><i class="ti-user"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.portuguese') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#en" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.english') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#es" role="tab">
                            <span class="hidden-sm-up"><i class="ti-agenda"></i></span>
                            <span class="hidden-xs-down">{{ __('messages.spanish') }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="pt-br" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-7 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.name') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="name[pt-br]" value="{{ old('name.pt-br') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('page_group_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.page-groups.model.name') }}</strong>
                                </label>
                                <select class="form-control" name="page_group_id" onchange="handlePageGroupChange(this)">
                                    <option value></option>
                                    @foreach ($pageGroups as $pageGroup)
                                        <option value="{{ $pageGroup->id }}"  @if (old('page_group_id') == $pageGroup->id) selected @endif>{{ $pageGroup->getTranslation('name', 'pt-br') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('is_active')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.is_active') }}</strong>
                                </label>
                                <select class="form-control" name="is_active" onchange="handleIsActiveChange(this)">
                                    <option value="1" @if (old('is_active', 1) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                    <option value="0" @if (old('is_active', 1) == 0) selected @endif>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.title') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="title[pt-br]" value="{{ old('title.pt-br') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('slug')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.slug') }}</strong>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="slug[pt-br]" value="{{ old('slug.pt-br') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('content')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.content') }}</strong>
                                </label>
                                <textarea rows="10" class="form-control summernote" name="content[pt-br]">{{ old('content.pt-br') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_title') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_title[pt-br]">{{ old('og_title.pt-br') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_description')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_description') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_description[pt-br]">{{ old('og_description.pt-br') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_keywords')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_keywords') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_keywords[pt-br]">{{ old('og_keywords.pt-br') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="en" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-7 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.name') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="name[en]" value="{{ old('name.en') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('page_group_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.page-groups.model.name') }}</strong>
                                </label>
                                <select class="form-control" name="page_group_id" onchange="handlePageGroupChange(this)">
                                    <option value></option>
                                    @foreach ($pageGroups as $pageGroup)
                                        <option value="{{ $pageGroup->id }}"  @if (old('page_group_id') == $pageGroup->id) selected @endif>{{ $pageGroup->getTranslation('name', 'en') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('is_active')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.is_active') }}</strong>
                                </label>
                                <select class="form-control" name="is_active" onchange="handleIsActiveChange(this)">
                                    <option value="1" @if (old('is_active', 1) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                    <option value="0" @if (old('is_active', 1) == 0) selected @endif>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.title') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="title[en]" value="{{ old('title.en') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('slug')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.slug') }}</strong>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="slug[en]" value="{{ old('slug.en') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('content')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.content') }}</strong>
                                </label>
                                <textarea rows="10" class="form-control summernote" name="content[en]">{{ old('content.en') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_title') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_title[en]">{{ old('og_title.en') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_description')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_description') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_description[en]">{{ old('og_description.en') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_keywords')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_keywords') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_keywords[en]">{{ old('og_keywords.en') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="es" role="tabpanel">
                        <div class="row">
                            <div class="form-group col-md-7 @if($errors->has('name')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.name') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="name[es]" value="{{ old('name.es') }}" />
                            </div>
                            <div class="form-group col-md-3 @if($errors->has('page_group_id')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.page-groups.model.name') }}</strong>
                                </label>
                                <select class="form-control" name="page_group_id" onchange="handlePageGroupChange(this)">
                                    <option value></option>
                                    @foreach ($pageGroups as $pageGroup)
                                        <option value="{{ $pageGroup->id }}"  @if (old('page_group_id') == $pageGroup->id) selected @endif>{{ $pageGroup->getTranslation('name', 'es') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2 @if($errors->has('is_active')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.is_active') }}</strong>
                                </label>
                                <select class="form-control" name="is_active" onchange="handleIsActiveChange(this)">
                                    <option value="1" @if (old('is_active', 1) == 1) selected @endif>{{ __('messages.yes') }}</option>
                                    <option value="0" @if (old('is_active', 1) == 0) selected @endif>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.title') }}</strong>
                                </label>
                                <input type="text" class="form-control" name="title[es]" value="{{ old('title.es') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('slug')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.slug') }}</strong>
                                </label>
                                <input type="text" class="form-control text-lowercase" name="slug[es]" value="{{ old('slug.es') }}" />
                            </div>
                            <div class="form-group col-md-12 @if($errors->has('content')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.content') }}</strong>
                                </label>
                                <textarea rows="10" class="form-control summernote" name="content[es]">{{ old('content.es') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_title')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_title') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_title[es]">{{ old('og_title.es') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_description')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_description') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_description[es]">{{ old('og_description.es') }}</textarea>
                            </div>
                            <div class="form-group col-md-4 @if($errors->has('og_keywords')) has-danger @endif">
                                <label class="form-control-label">
                                    <strong>{{ __('resources.pages.model.og_keywords') }}</strong>
                                </label>
                                <textarea rows="5" class="form-control" name="og_keywords[es]">{{ old('og_keywords.es') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.pages.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('styles')
    <link rel="stylesheet" href="/backend/vendors/summernote/dist/summernote.css" />
@endpush

@push('scripts')
    <script src="/backend/vendors/summernote/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 450,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
        });

        function handleIsActiveChange(e) {
            let select = $(e);
            let selects = $('select[name="is_active"]');

            selects.val(select.val());
        }

        function handlePageGroupChange(e) {
            let select = $(e);
            let selects = $('select[name="page_group_id"]');

            selects.val(select.val());
        }
    </script>
@endpush
