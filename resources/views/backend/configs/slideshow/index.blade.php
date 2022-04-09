@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.images.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Slideshow</li>
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
    @forelse ($images as $image)
        <div class="col-md-4">
            <div class="card gallery-image-card">
                <a href="{{ route('backend.configs.slideshow.edit', $image) }}" >
                    <div class="label-default-image">
                        {!! $image->getFlagLabel() !!}
                        {!! $image->getIsDefaultLabel() !!}
                        <span class="label label-default text-primary">
                            {{ $image->type }}
                        </span>
                    </div>
                    <img class="card-img-top img-responsive" src="{{ $image->getUrl() }}" alt="{{ $image->title }}">
                </a>
                <div class="card-body">
                    <h4 class="card-title">{{ $image->title }}</h4>
                    <p class="card-text">{{ $image->subtitle }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('backend.images.edit', $image) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="{{ route('backend.images.destroy', $image) }}" class="text-danger btn-sm delete pull-right">
                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <div class="alert alert-info">
                Nenhuma imagem para ser exibida
            </div>
        </div>
    @endforelse
</div>

@endsection