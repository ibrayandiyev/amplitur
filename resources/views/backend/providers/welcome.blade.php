@extends('backend.template.register')

@section('content')

<div class="row">
    <div class="bg-white mt-60 mb-auto">
        <div class="card w-100 bg-white">
            <div class="card-body bg-white">
                <h4 class="card-title">{{__('resources.label_prov_register')}}</h4>
                <p class="card-text">{{__('resources.label_prov_register_msg')}} <strong>{{ $provider->email }}</strong> {{__('resources.label_prov_register_msg2')}}</p>
                    <a href="{{ route('frontend.index') }}" class="btn btn-info">www.amplitur.com</a>
            </div>
        </div>
    </div>
</div>
@endsection
