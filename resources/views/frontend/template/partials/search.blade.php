<div class="busca busca--prereserva">
	<div class="largura-site ma">
		<form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
			<div class="busca__grid">
				<label for="fbusca" class="esconde-vis">{{ __('frontend.geral.buscar') }}</label>
				<input class="busca__campo" type="search" placeholder="{{ __('frontend.geral.busca_placeholder') }} " id="fbusca" name="q" value="" required />
				<button class="busca__botao" type="submit">
					<i class="fas fa-search"></i>
					<span class="esconde-vis">{{ __('frontend.geral.buscar') }}</span>
				</button>
			</div>
        </form>
    </div>
</div>
