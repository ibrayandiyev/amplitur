@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-5">
        <h3 class="text-themecolor">{{ __('resources.categories.edit') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.categories.index') }}">{{ __('resources.categories.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </div>
    <div class="col-md-7">
        <div class="float-right">
            <a href="{{ route('backend.categories.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.categories.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="clientForm" method="post" action="{{ route('backend.categories.update', $category->id) }}" autocomplete="off">
                <input type="hidden" name="_method" value="put" />
                @csrf
                <div class="labelx label-service">
                    {{ __('resources.categories.info') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label">{{ __('resources.categories.model.name') }}</label>
                            <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control text-uppercase" value="{{ old('name', $category->name) }}" required/>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-control-label">{{ __('resources.categories.model.type') }}</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" name="type" required>
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="{{ \App\Enums\CategoryType::EVENT }}" @if (old('type', $category->type) == \App\Enums\CategoryType::EVENT) selected @endif>{{ __('resources.categories.model.types.event') }}</option>
                                <option value="{{ \App\Enums\CategoryType::PACKAGE }}" @if (old('type', $category->type) == \App\Enums\CategoryType::PACKAGE) selected @endif>{{ __('resources.categories.model.types.package') }}</option>
                                <option value="{{ \App\Enums\CategoryType::HOTEL }}" @if (old('type', $category->type) == \App\Enums\CategoryType::HOTEL) selected @endif>{{ __('resources.categories.model.types.hotel') }}</option>
                                <option value="{{ \App\Enums\CategoryType::OTHER }}" @if (old('type', $category->type) == \App\Enums\CategoryType::OTHER) selected @endif>{{ __('resources.categories.model.types.other') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3" data-event-only>
                            <label class="form-control-label">{{ __('resources.categories.model.duration') }}</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" name="flags[DURATION]">
                                <option value="one-day" @if (old('duration', $category->getFlag('DURATION')) == "one-day") selected @endif>{{ __('resources.categories.model.durations.one-day') }}</option>
                                <option value="range-date" @if (old('duration', $category->getFlag('DURATION')) == "range-date") selected @endif>{{ __('resources.categories.model.durations.range-date') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label class="form-control-label">Slug</label>
                            <span class="text-danger">*</span>
                            <input type="text" name="slug" class="form-control text-lowercase" value="{{ old('slug', $category->slug) }}" required/>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-control-label">{{ __('resources.categories.model.group') }}</label>
                            <select class="form-control" name="parent_id">
                                <option value="">{{ __('messages.select') }}</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if ($category->id == old('parent_id', $category->parent_id)) selected @endif>{{ $category->typeText }} / {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label">{{ __('resources.categories.model.description') }}</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.categories.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var selectCategoryType = $('select[name="type"]');

    handleOnlyEvents(selectCategoryType.val());

    selectCategoryType.change(function () {
        handleOnlyEvents($(this).val());
    });

    function handleOnlyEvents(categoryType) {
        var selectDuration = $('[data-event-only]');

        if (categoryType == '{{ \App\Enums\CategoryType::EVENT }}') {
            selectDuration.show();
        } else {
            selectDuration.hide();
        }
    }
});
</script>
@endpush
