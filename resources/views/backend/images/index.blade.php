@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-6">
        <h3 class="text-themecolor">{{ __('resources.images.name-plural') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.images.name') }}</li>
        </ol>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('backend.images.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i>
                {{ __('resources.images.create') }}
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('backend.images.index') }}" class="btn @if (!empty($letter)) btn-primary @else btn-secondary @endif">#</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'A']) }}" class="btn @if (!empty($letter) && $letter == 'A') btn-secondary @else btn-primary @endif">A</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'B']) }}" class="btn @if (!empty($letter) && $letter == 'B') btn-secondary @else btn-primary @endif">B</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'C']) }}" class="btn @if (!empty($letter) && $letter == 'C') btn-secondary @else btn-primary @endif">C</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'D']) }}" class="btn @if (!empty($letter) && $letter == 'D') btn-secondary @else btn-primary @endif">D</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'E']) }}" class="btn @if (!empty($letter) && $letter == 'E') btn-secondary @else btn-primary @endif">E</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'F']) }}" class="btn @if (!empty($letter) && $letter == 'F') btn-secondary @else btn-primary @endif">F</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'G']) }}" class="btn @if (!empty($letter) && $letter == 'G') btn-secondary @else btn-primary @endif">G</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'H']) }}" class="btn @if (!empty($letter) && $letter == 'H') btn-secondary @else btn-primary @endif">H</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'I']) }}" class="btn @if (!empty($letter) && $letter == 'I') btn-secondary @else btn-primary @endif">I</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'J']) }}" class="btn @if (!empty($letter) && $letter == 'J') btn-secondary @else btn-primary @endif">J</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'K']) }}" class="btn @if (!empty($letter) && $letter == 'K') btn-secondary @else btn-primary @endif">K</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'L']) }}" class="btn @if (!empty($letter) && $letter == 'L') btn-secondary @else btn-primary @endif">L</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'M']) }}" class="btn @if (!empty($letter) && $letter == 'M') btn-secondary @else btn-primary @endif">M</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'N']) }}" class="btn @if (!empty($letter) && $letter == 'N') btn-secondary @else btn-primary @endif">N</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'O']) }}" class="btn @if (!empty($letter) && $letter == 'O') btn-secondary @else btn-primary @endif">O</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'P']) }}" class="btn @if (!empty($letter) && $letter == 'P') btn-secondary @else btn-primary @endif">P</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'Q']) }}" class="btn @if (!empty($letter) && $letter == 'Q') btn-secondary @else btn-primary @endif">Q</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'R']) }}" class="btn @if (!empty($letter) && $letter == 'R') btn-secondary @else btn-primary @endif">R</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'S']) }}" class="btn @if (!empty($letter) && $letter == 'S') btn-secondary @else btn-primary @endif">S</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'T']) }}" class="btn @if (!empty($letter) && $letter == 'T') btn-secondary @else btn-primary @endif">T</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'U']) }}" class="btn @if (!empty($letter) && $letter == 'U') btn-secondary @else btn-primary @endif">U</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'V']) }}" class="btn @if (!empty($letter) && $letter == 'V') btn-secondary @else btn-primary @endif">V</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'W']) }}" class="btn @if (!empty($letter) && $letter == 'W') btn-secondary @else btn-primary @endif">W</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'X']) }}" class="btn @if (!empty($letter) && $letter == 'X') btn-secondary @else btn-primary @endif">X</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'Y']) }}" class="btn @if (!empty($letter) && $letter == 'Y') btn-secondary @else btn-primary @endif">Y</a>
                <a href="{{ route('backend.images.filter', ['letter' => 'Z']) }}" class="btn @if (!empty($letter) && $letter == 'Z') btn-secondary @else btn-primary @endif">Z</a>
            </div>
        </div>
    </div>
    @forelse ($images as $image)
        <div class="col-md-4">
            <div class="card gallery-image-card">
                <a href="{{ route('backend.images.edit', $image) }}" >
                    <div class="label-default-image">
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
                    <a href="{{ route('backend.images.destroy', $image) }}" token="{{ csrf_token() }}" class="text-danger btn-sm delete pull-right">
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
