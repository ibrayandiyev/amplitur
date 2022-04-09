<footer class="rodape grupo">
	<div class="largura-site ma">
		<div class="rodape__certificacao">
			<p class="rodape__certificacao-texto">
				{{ __('geral.site_certificado') }}
			</p>

			@include('frontend.template.scripts.certifications')
		</div>
	</div>
	<div class="rodape__conteudo">
		<div class="largura-site ma">
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
		</div>
	</div>
</footer>
