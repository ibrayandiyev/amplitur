@php
	$pageGroups = app(\App\Repositories\PageGroupRepository::class)->list();
@endphp

<footer class="rodape grupo">
	<div class="largura-site ma">
		<div class="rodape__certificacao">
			<p class="rodape__certificacao-texto">
				{{ __('geral.site_certificado') }}
			</p>

			@include('frontend.template.scripts.certifications')
		</div>
	</div>
	<!-- /.largura-site ma -->
	<div class="rodape__social">
		<div class="largura-site ma">
			<h2 class="rodape__social-titulo">{{ __('frontend.geral.rodape_social') }}</h2>
			<ul class="rodape__social-lista">
				<li class="rodape__social-item">
					<a class="rodape__social-link" href="http://www.twitter.com/ampliturturismo" rel="external">
						<i class="fab fa-twitter-square"></i>
					</a>
				</li>
				<li class="rodape__social-item">
					<a class="rodape__social-link" href="https://www.facebook.com/amplitur.operadoradeturismo" rel="external">
						<i class="fab fa-facebook"></i>
					</a>
				</li>
				<li class="rodape__social-item">
					<a class="rodape__social-link" href="https://instagram.com/aamplituroperadora/" rel="external">
						<i class="fab fa-instagram"></i>
					</a>
				</li>
			</ul>
		</div>
		<!-- /.largura-site.ma -->
	</div>
	<!-- /.rodape__social -->
	<div class="rodape__conteudo">
		<div class="largura-site ma">
			<div class="rodape__principal flex-grid">
				<ul class="rodape__modulos flex-grid">
					@foreach ($pageGroups as $pageGroup)
						<li class="rodape__modulo">
							<h2 class="rodape__titulo">{!! $pageGroup->getTranslation('name', language()) !!}</h2>
							<ul class="rodape__lista-links">
							@foreach($pageGroup->pages as $page)
								@if(!$page->isActive())
									@continue
								@endif
								<li>
									<a href="{{ route('frontend.pages.show', $page->getTranslation('slug', language())) }}">{!! $page->getTranslation('name', language()) !!}</a>
								</li>
							@endforeach
							</ul>
						</li>
					@endforeach
				</ul>
				<!-- /.rodape__modulos -->
				<div class="rodape__pagamento">
					<h2 class="rodape__titulo">{{ __('frontend.geral.rodape_formapag') }}</h2>
					<div class="rodape__formas-pagamento">
						<h3 class="rodape__formas-titulo">{{ __('frontend.geral.rodape_cartoes') }}</h3>
						<div class="cards cards-full"><img src="{{ asset('/frontend/images/icones/cartao.png') }}"></div>
					</div>
					<div class="flex-grid flex-grid--gutters">
						<div class="rodape__formas-pagamento">
							<h3 class="rodape__formas-titulo">{{ __('frontend.geral.rodape_boleto') }}</h3>
							<div class="cards cards-boleto"><img src="{{ asset('/frontend/images/icones/boleto.png') }}"></div>
						</div>
						<div class="rodape__formas-pagamento">
							<h3 class="rodape__formas-titulo">{{ __('frontend.geral.rodape_trf') }}</h3>
							<div class="cards cards-transferencia"><img src="{{ asset('/frontend/images/icones/transfer.png') }}"></div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.rodape__principal -->
			<div class="rodape__extra">
				<div class="flex-grid flex-grid--v-center">
					<div class="grid-cell-xs--12 grid-cell-sm--6">
						<small class="rodape__info">
                            AMP Travels Ltd.
							<br />
                            138, Chapel Street, Salford M3 6DE - United Kingdom
							<br/>
                            Company Number 13500131.
							<br/>
                            © 2022 AMP Travels . {{ __('frontend.geral.todos_direitos') }}

						</small>
					</div>
					<div class="rodape__marcas-gm grid-cell-xs--12 grid-cell-sm--6 grupo">
						<div class="rodape__marcas">
							<img class="rodape-cadastur" src="/frontend/images/estrutura/logo_cadastur.png" alt="Cadastur" />
							<img class="rodape-embratur" src="/frontend/images/estrutura/logo_embratur.png" alt="Embratur" />
							<img class="rodape-iata" src="/frontend/images/estrutura/logo_iata.png" alt="IATA" />
						</div>
						<a title="Desenvolvido por Guilherme Müller" href="#" rel="external" class="rodape__gm">
							<img src="/frontend/images/gm_branca@2x.png" alt="Desenvolvido por Guilherme Müller" />
						</a>
					</div>
				</div>
			</div>
			<!-- /.rodape__extra -->
		</div>
		<!-- /.largura-site ma -->
	</div>
	<!-- /.rodape__conteudo -->
</footer>
