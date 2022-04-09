@extends('frontend.template.page')

@section('structure-data')
@include('frontend.template.scripts.structure-data-pre-booking')
@endsection

@section('title'){{__('frontend.pacotes.pre_reserva')}} {{ $event->getTitle() }} - Amp Travels @endsection
@section('description') {{$event->meta_description}}@endsection
@section('keywords') {{$event->meta_keywords}}@endsection
@section('width'){{712}}@endsection
@section('height'){{360}}@endsection
@section('image_url'){{ $event->getThumbnail2xUrl() }}@endsection
@section('url'){{ $event->getPrebookingUrl()}} @endsection

@section('content')
    <main class="conteudo grupo">
		<div class="largura-site ma">
            <header class="pacote__header">
                <span class="pacote__gravata">{{ __('frontend.pacotes.pre_reserva') }}</span>
                <div class="grid">
                    <div class="grid-xs--12 grid-md--8">
                        <h1 class="pacote__titulo">
                            <span class="pacote__nome">{{ $event->getTitle() }}</span>
                            <span class="pacote__subnome">{{ $event->getLocation() }}</span>
                        </h1>
                    </div>
                    <div class="grid-xs--12 grid-md--4">
                        <img class="prereserva-img" src="{{ $event->getThumbnailUrl() }}" alt="{{ $event->getTitle() }}" />
                    </div>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5c100eb1d412bfe9"></script>
                    <div class="addthis_inline_share_toolbox"></div>
                </div>
            </header>

            <div class="form form--auto-style mb2">
                <form action="{{ route('frontend.prebookings.store', [$event, $slug]) }}" method="post" accept-charset="utf-8">
                    @csrf
                    <div class="form__campo">
                        <label>{{ __('frontend.forms.nome') }}</label>
                        <input type="text" id="nome" name="name" value="">
                    </div>

                    <div class="form__campo">
                        <label>{{ __('frontend.forms.email') }}</label>
                        <input type="email" id="email" name="email" value="">
                    </div>

                    <fieldset class="grid">
                        <div class="form__campo grid-xs--12 grid-md--8">
                            <label>{{ __('frontend.forms.cidade') }}</label>
                            <input type="text" id="cidade" name="city" value="">
                        </div>

                        <div class="form__campo grid-xs--12 grid-md--4">
                            <label>{{ __('frontend.forms.pais') }}</label>
                            <select name="country" id="pais" placeholder="DDI">
                                 <option value>{{ __('messages.select') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->iso2 }}" @if(old('address.country') == $country->iso2) selected @endif>{{ country($country) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <input type="hidden" name="event_id" value="{{ $event->id }}" />

                    <div class="form__campo">
                        <div class="g-recaptcha" data-sitekey="6Lek_xgUAAAAANn00gPK8GlF7c1p9-zr22HGDAYZ"></div>
                        <input type="hidden" id="recaptcha" name="recaptcha" value="03AGdBq27JbAX6ROB9flZ_I3Klmnk9KWQQXT7jyUBYoA4_VmZFZp7C5iNOyP603ENxQYJRYCT6Sz0ENO9BGZR0RQhho1MEfcy2ebirjQtAlMOQCtGDg4fjZJvOA7OpUl_UB9YlOow6Z_qoa_2jb77z1b65j_ywI3Dn0mV3_hlrocIq5ybS5FWR3FYbNZ7xsLxW-DcBQZRRh2m8Xnrcr1m_iyggCVe1tRJ0fHUQSluEToDaaNjWJt9g0nbkoyljvDKLZCUJcnPJHDj9fAFS8kP3hx_dbQGklm08NsKv7AIaDLUsbnIDe19rHB7V8HUzq61IldM9uCnocuvGSSLpcV2ejGlfec_PdHiI_EkfzyMrvb_YWeHpzVT-f8PojF_5wImElFqmZbB8LdF5T2afKg986rZk1re6RC67htV8shZJVqTwo_dMCmjKIrVEZNUOjn74ojJQ3cW8g0P5uXHwBa7I9mJejMshnCM9tO6cMyBCgAO5Yliu0sGuzyPVpFnNfMkOzfQdGfo-uLZu">
                    </div>

                    <div class="a-centro">
                        <button class="botao botao--submit" type="submit" name="submit">
                            {{ __('frontend.forms.confirmar_prereserva') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="pacote_descricao">
                <h2 class="pacote__subtitulo">{{ __('frontend.pacotes.mais_info') }}</h2>
                <div class="pacote__descricao">
				<div class="corpo-texto">
                    {!! $event->getDescription() !!}
                </div>
			    </div>
            </div>

            <div class="pacote__galeria">
                @component('frontend.template.components.gallery')
                    @slot('event', $event)
                @endcomponent
            </div>
        </div>
    </main>
@endsection

@push('scripts')
@include('frontend.template.scripts.mapable')
@include('frontend.template.scripts.gallery-slideshow')
@endpush
