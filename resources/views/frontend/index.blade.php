@extends('frontend.template.default')

@section('structure-data')
@include('frontend.template.scripts.structure-data-searchpage')
@include('frontend.template.scripts.structure-data-home')
@endsection

@section('content')
    <main class="conteudo grupo">
		<div class="largura-site ma">
			@if (!empty($slideshowImages))
				<div class="slideshow">
					<div class="cycle-slideshow" data-cycle-slides="> .slide">
						@foreach ($slideshowImages as $slideshowImage)
							<div class="slide" style="background-image: url({{ $slideshowImage->getUrl() }});">
								<a class="slide__link" href="{{ $slideshowImage->link }}"></a>
							</div>
						@endforeach
						<div class="cycle-pager"></div>
					</div>
				</div>
			@endif
			<div class="busca busca--home">
                <div class="largura-site ma">
                    <form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
                        <div class="busca__grid">
                            <label for="fbusca" class="esconde-vis">{{ __('frontend.geral.buscar') }} </label>
                            <input class="busca__campo" type="search" placeholder="{{ __('frontend.geral.busca_placeholder') }}" id="fbusca" name="q" value="" required>
                            <button class="busca__botao" type="submit">
                                <i class="fas fa-search"></i>
                                <span class="esconde-vis">{{ __('frontend.geral.buscar') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

			<div class="pacotes-categoria">
				<h2 class="pacotes-categoria__titulo">{{ __('frontend.pacotes.excursao') }}</h2>

				<ul class="pacotes-lista flex-grid flex-grid--gutters mb">
					@foreach ($packages as $package)
						<li class="pacotes-lista__item mb">
							@component('frontend.template.components.package')
								@slot('package', $package)
							@endcomponent
						</li>
					@endforeach
				</ul>
			</div>

			<div class="pacotes-categoria">
				<h2 class="pacotes-categoria__titulo">{{ __('frontend.pacotes.pre_reserva') }}</h2>

				<ul class="pacotes-lista flex-grid flex-grid--gutters mb">
					@foreach ($prebookingEvents as $prebookingEvent)
					<li class="pacotes-lista__item mb">
						@component('frontend.template.components.prebooking')
							@slot('event', $prebookingEvent)
						@endcomponent
					</li>
					@endforeach
				</ul>
			</div>

			<div class="pacotes-tabelas flex-grid flex-grid--gutters mb">
				<div class="grid-cell-xs--12 grid-cell-md--6">
					<div class="pacotes-tabelas__coluna">
						<header>
							<h2 class="pacotes-tabelas__titulo pacotes-categoria__titulo">Top 10</h2>
						</header>

						<table class="pacotes-tabela mb">
							<tbody>
								@if (count($topPackages) > 0)
									@foreach ($topPackages as $package)
										@component('frontend.template.components.package-minimal')
											@slot('package', $package)
										@endcomponent
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>

				<div class="grid-cell-xs--12 grid-cell-md--6">
					<div class="pacotes-tabelas__coluna">
						<header>
							<h2 class="pacotes-tabelas__titulo pacotes-categoria__titulo">{{ __('frontend.geral.proximos_eventos') }}</h2>
						</header>

						<table class="pacotes-tabela mb">
							<tbody>
								@if (count($nextPackages) > 0)
									@foreach ($nextPackages as $package)
										@component('frontend.template.components.package-minimal')
											@slot('package', $package)
										@endcomponent
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="a-centro mb2">
				<a href="{{ route('frontend.packages.index') }}" class="botao botao--padrao">
					<i class="fas fa-plus-circle"></i> {{ __('frontend.geral.ver_pacotes') }}</a>
			</div>

			<div class="newsletter">
				<header class="newsletter__header">
					<h2 class="newsletter__titulo">{{ __('frontend.geral.news_titulo') }}</h2>
					<span class="newsletter__subtitulo">{{ __('frontend.geral.news_subtitulo') }}</span>
				</header>
				<div class="newsletter__conteudo">
					<form action="{{ route('frontend.newsletter.store') }}" method="post" accept-charset="utf-8">
						@csrf
						<div class="newsletter__grid">
							<div class="newsletter__campo">
								<label class="esconde-vis" for="n-nome">{{ __('frontend.forms.nome') }}</label>
								<input type="text" id="n-nome" name="name" placeholder="Nome" value="">
							</div>
							<div class="newsletter__campo">
								<label class="esconde-vis" for="n-email">E-mail</label>
								<input type="email" id="n-email" name="email" placeholder="E-mail" value="">
							</div>
							<div class="newsletter__captcha">
								<div class="g-recaptcha" data-sitekey="6Lek_xgUAAAAANn00gPK8GlF7c1p9-zr22HGDAYZ"></div>
								<input type="hidden" id="recaptcha" name="recaptcha" value="1">
							</div>
							<button class="newsletter__botao" type="submit" name="submit">
								{{ __('frontend.geral.news_botao') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
@endsection
