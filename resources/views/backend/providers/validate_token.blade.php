@extends('backend.template.register')

@section('content')
<div class="row">
    <div class="bg-white mt-60 mb-auto">
        <div class="card w-100 bg-white">
            <div class="card-body bg-white">
                <h4 class="card-title">{{__('mail.provider.valid_registry.head')}}</h4>
                <p class="card-text">{{__('mail.provider.valid_registry.head_msg')}}</p>
                    <p class="card-text">{{__('mail.provider.valid_registry.link_msg')}} </p>
                    <p class="card-text">{{__('mail.provider.valid_registry.doubt_msg')}}</p>
                    <a href="{{ route('backend.index') }}" class="btn btn-info">{{__('mail.provider.valid_registry.link_acess')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection
