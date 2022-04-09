@php
    $resource = $package ?? $event ?? $offer;
@endphp

@if ($resource)
<div class="multiple-items {{ $class ?? '' }}" style="margin: 20px 0px;">
    @foreach ($resource->getGallery() as $image)
        <div>
            <img src='{{ $image->getThumbnailUrl() }}' title='{{ $image->title }}' />
            {{ $image->title }}
        </div>
    @endforeach
</div>
@endif