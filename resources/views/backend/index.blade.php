@extends('backend.template.default')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('resources.top_10') }}</h4>
                <h6 class="card-subtitle">{{ __('resources.mais_acessados') }}</h6>
                <a href="{{ route('backend.packages.index') }}" class="btn btn-sm btn-primary pull-right" style="margin-top: -55px; z-index: 1;">
                    {{ __('messages.see-all') }}
                </a>
                <div class="mt-20 no-wrap">
                    <table id="packagesTable" class="table table-bordered table-striped table-hover table vm no-th-brd">
                        <thead>
                            <tr>
                                <th>{{ __('resources.events.name') }}</th>
                                <th class="text-center">{{ __('resources.packages.model.visits') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($packages as $package)
                                    <tr>

                                        <td class="text-uppercase">
                                            <div class="labelx label-top-pack">
                                                <div>
                                                    <a href="{{ route('backend.packages.edit', $package) }}" style="color: white">{{ $package->name }} </a>
                                                </div>
                                                <div class="labelx label-sub-pack">
                                                    {{ $package->date}} - {{$package->location }} || {{ city($package->address->city) }} - {{ state($package->address->country, $package->address->state) }} - {{ country($package->address->country) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-uppercase text-center">
                                            <span class="label label-light-inverse">{{ $package->visits }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('resources.ofertas') }}</h4>
                <h6 class="card-subtitle">{{ __('resources.suas_ofertas') }}</h6>
                <a href="{{ route('backend.offers.index') }}" class="btn btn-sm btn-primary pull-right" style="margin-top: -55px; z-index: 1;">
                    {{ __('messages.see-all') }}
                </a>
                <div class="mt-20 no-wrap">
                    <table id="offersTable" class="table table-bordered table-striped table-hover table vm no-th-brd">
                        <thead>
                            <tr>
                                <th>{{ __('resources.packages.name') }}</th>
                                <th class="text-center">{{ __('resources.offers.model.type') }}</th>
                                <th class="text-center">{{ __('resources.offers.model.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $packageId = 0;
                            @endphp
                            @foreach ($offers->sortBy("package_id") as $offer)
                                    @if($offer->package_id != $packageId)
                                        <tr>
                                            <td colspan="3" class="text-uppercase">
                                                <div class="labelx label-pack">
                                                {{ $offer->package->name }}
                                                <span class="labelx label-sub-pack">
                                                    {{ $offer->package->date}} - {{$offer->package->location }} || {{ city($offer->package->address->city) }} - {{ state($offer->package->address->country, $offer->package->address->state) }} - {{ country($offer->package->address->country) }} </span>
                                                </div>
                                            </td>
                                        </tr>
                                        @php 
                                            $packageId = $offer->package_id;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-left"><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer->id]) }}">{!! $offer->typeLabel !!}</a></td>
                                        <td class="text-center">{!! $offer->provider->name !!}</td>
                                        <td class="text-center">{!! $offer->statusLabel !!}</td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
