@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.images.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.images.index') }}">Slideshow</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.images.create') }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.configs.slideshow.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.images.create') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form id="imagesForm" method="post" action="{{ route('backend.configs.slideshow.store') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" class="form-control" name="type" value="package" />
                <div class="labelx label-service">
                    {{ __('resources.images.name-plural') }}
                </div>
                <div class="card-body">
                    <div class="cropping-tool" style="display: none;">
                        <div class="row">
                            <div class="col-md-9 pa-20">
                                <div class="img-container">
                                    <img id="image" src="" class="img-responsive" alt="Picture">
                                </div>
                            </div>
                            <div class="col-md-3 pa-20">
                                <div class="docs-preview clearfix">
                                    <div class="img-preview preview-lg"></div>
                                    <div class="img-preview preview-md"></div>
                                </div>
                                <input type="hidden" class="form-control" id="dataAspectRatio" value="2.85">
                                <input type="hidden" class="form-control" name="image_attributes[x]" id="dataX">
                                <input type="hidden" class="form-control" name="image_attributes[y]" id="dataY">
                                <input type="hidden" class="form-control" name="image_attributes[width]" id="dataWidth">
                                <input type="hidden" class="form-control" name="image_attributes[height]" id="dataHeight">
                                <input type="hidden" class="form-control" name="image_attributes[rotate]" id="dataRotate">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 docs-buttons">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" data-method="setDragMode" data-option="move" title="Move"> <span class="docs-tooltip" data-toggle="tooltip" title="Move"> <span class="fa fa-arrows"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-info" data-method="setDragMode" data-option="crop" title="Crop"> <span class="docs-tooltip" data-toggle="tooltip" title="Crop"> <span class="fa fa-crop"></span> </span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" data-method="zoom" data-option="0.1" title="Zoom In"> <span class="docs-tooltip" data-toggle="tooltip" title="Zoom In"> <span class="fa fa-search-plus"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-success" data-method="zoom" data-option="-0.1" title="Zoom Out"> <span class="docs-tooltip" data-toggle="tooltip" title="Zoom Out"> <span class="fa fa-search-minus"></span> </span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="move" data-option="-10" data-second-option="0" title="Move Left"> <span class="docs-tooltip" data-toggle="tooltip" title="Move Left"> <span class="fa fa-arrow-left"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="move" data-option="10" data-second-option="0" title="Move Right"> <span class="docs-tooltip" data-toggle="tooltip" title="Move Right"> <span class="fa fa-arrow-right"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="move" data-option="0" data-second-option="-10" title="Move Up"> <span class="docs-tooltip" data-toggle="tooltip" title="Move Up"> <span class="fa fa-arrow-up"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="move" data-option="0" data-second-option="10" title="Move Down"> <span class="docs-tooltip" data-toggle="tooltip" title="Move Down"> <span class="fa fa-arrow-down"></span> </span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="rotate" data-option="-45" title="Rotate Left"> <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Left"> <span class="fa fa-rotate-left"></span> </span>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-outline" data-method="rotate" data-option="45" title="Rotate Right"> <span class="docs-tooltip" data-toggle="tooltip" title="Rotate Right"> <span class="fa fa-rotate-right"></span> </span>
                                    </button>
                                </div>
                                <div class="modal docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                            </div>
                                            <div class="modal-body"></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 @if($errors->has('image')) has-danger @endif">
                            <label for="inputImage">
                                <strong>{{ __('resources.images.name') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" id="inputImage" accept="image/*">
                                <label class="custom-file-label" for="image">{{ __('messages.choose-file') }}</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('title')) has-danger @endif">
                            <label class="form-group-label">
                                <strong>{{ __('resources.images.model.title') }}</strong>
                            </label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" />
                        </div>
                        <div class="form-group col-md-6 @if($errors->has('subtitle')) has-danger @endif">
                            <label class="form-group-label">
                                <strong>{{ __('resources.images.model.subtitle') }}</strong>
                            </label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}" />
                        </div>
                        <div class="form-group col-md-3 @if($errors->has('language')) has-danger @endif">
                            <label class="form-control-label">
                                <strong>{{ __('resources.language.language') }}</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" name="language">
                                <option value>{{ __('messages.select') }}</option>
                                <option value="{{ App\Enums\Language::PORTUGUESE }}" @if(old('language') == App\Enums\Language::PORTUGUESE) selected @endif>{{ __('resources.language.portuguese') }}</option>
                                <option value="{{ App\Enums\Language::ENGLISH }}" @if(old('language') == App\Enums\Language::ENGLISH) selected @endif>{{ __('resources.language.english') }}</option>
                                <option value="{{ App\Enums\Language::SPANISH }}" @if(old('language') == App\Enums\Language::SPANISH) selected @endif>{{ __('resources.language.spanish') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5 @if($errors->has('link')) has-danger @endif">
                            <label class="form-group-label">
                                <strong>{{ __('resources.images.model.link') }}</strong>
                            </label>
                            <input type="text" name="link" class="form-control" value="{{ old('link') }}" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary save">
                        <i class="fa fa-save"></i> {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('backend.images.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="/backend/vendors/cropper/cropper.min.js"></script>
    <script type="text/javascript" src="/backend/vendors/cropper/cropper-init.js"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/cropper/cropper.min.css" />
@endpush
