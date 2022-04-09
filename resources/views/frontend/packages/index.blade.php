@extends('frontend.template.default')

@section('content')

    <div class="busca busca--pacotes">
        <div class="largura-site ma">
            <form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
                <div class="busca__grid">
                    <label for="fbusca" class="esconde-vis">{{ __('frontend.') }}Buscar</label>
                    <input class="busca__campo" type="search" placeholder="{{ __('frontend.geral.busca_placeholder') }} " id="fbusca" name="q" value="" required>
                    <button class="busca__botao" type="submit">
                        <i class="fas fa-search"></i>
                        <span class="esconde-vis">{{ __('frontend.') }}Buscar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="conteudo grupo">
        <div class="largura-site ma">
            <div class="largura-site2 ma">
	            <h1 class="pacotes-categoria__titulo mt">{{ __('frontend.') }}Todos os Pacotes</h1>
	            <table class="pacotes-tabela mb">
	                <tbody>
				        <tr class="pacotes-tabela__categoria">
			                <td colspan="4">{{ __('frontend.') }}PACOTES</td>
		                </tr>
			            <tr class="pacotes-tabela__tr" itemscope="" itemtype="http://schema.org/Event">
	                        <td class="td--data">
				                <time time class="pacotes-tabela__data" itemprop="startDate" datetime="2021-05-16" content="2021-05-16">16 Mai</time>
		                        <time itemprop="endDate" datetime="2021-05-16" content="2021-05-16"></time>
			                </td>
                            <td class="td--evento">
                                <header class="pacotes-tabela__header">
                                    <a itemprop="url" href="https://www.amplitur.com/pacotes/detalhes/1019/louis-tomlinson">
                                        <h2 itemprop="name" content="Excursão Louis Tomlinson" class="pacotes-tabela__titulo">Louis Tomlinson</h2>
                                        <span itemprop="location" itemscope="" itemtype="http://schema.org/Place" class="pacotes-tabela__subnome">
                                            <span itemprop="name">Espaço das Américas</span>
                                            <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">–
                                                <span itemprop="addressLocality">São Paulo</span>,
                                                <span itemprop="addressCountry">Brasil</span>
                                            </span>
                                        </span>
                                        <span itemprop="performer" itemscope="" itemtype="http://schema.org/Person">
                                            <meta itemprop="name" content="Louis Tomlinson" />
                                        </span>
                                        <meta itemprop="description" content="Louis Tomlinson" />
                                    </a>
                                </header>
                            </td>
                            <td class="td--icone"></td>
                            <td itemprop="offers" itemscope="" itemtype="http://schema.org/AggregateOffer" class="td--botao" style="height: 52px;">
                                <meta itemprop="url" content="https://www.amplitur.com/pacotes/detalhes/1019/louis-tomlinson" />
                                <meta itemprop="validFrom" content="2021-05-16" />
                                <meta itemprop="availability" content="InStock" />
                                <a href="https://www.amplitur.com/pacotes/detalhes/1019/louis-tomlinson" class="pacotes-lista__botao botao botao--comprar--alt">Comprar</a>
                            </td>
                        </tr>
                        <tr class="pacotes-tabela__categoria">
			                <td colspan="4">PACOTES ADIADOS - DATA A SER CONFIRMADA</td>
		                </tr>
			            <tr class="pacotes-tabela__tr" itemscope="" itemtype="http://schema.org/Event">
	                        <td class="td--data"></td>
                            <td class="td--evento">
                                <header class="pacotes-tabela__header">
                                    <a itemprop="url" href="https://www.amplitur.com/prereserva/1025/harry-styles">
                                        <h2 itemprop="name" content="Excursão Harry Styles" class="pacotes-tabela__titulo">Harry Styles</h2>
                                        <span itemprop="location" itemscope="" itemtype="http://schema.org/Place" class="pacotes-tabela__subnome">
                                            <span itemprop="name">Allianz Parque </span>
                                            <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">–
                                                <span itemprop="addressLocality">São Paulo</span>,
                                                <span itemprop="addressCountry">Brasil</span>
                                            </span>
                                        </span>
                                        <span itemprop="performer" itemscope="" itemtype="http://schema.org/Person">
                                            <meta itemprop="name" content="Harry Styles" />
                                        </span>
                                        <meta itemprop="description" content="Harry Styles" />
                                    </a>
                                </header>
                            </td>
                            <td class="td--icone"></td>
                            <td itemprop="offers" itemscope="" itemtype="http://schema.org/AggregateOffer" class="td--botao" style="height: 48px;">
                                <meta itemprop="url" content="https://www.amplitur.com/prereserva/1025/harry-styles" />
                                <meta itemprop="validFrom" content="0000-00-00" />
                                <meta itemprop="availability" content="InStock" />
                                <a href="https://www.amplitur.com/prereserva/1025/harry-styles" class="pacotes-lista__botao botao botao--pre-reservar--alt">Pré-reserva</a>
                            </td>
                        </tr>
				        <tr class="pacotes-tabela__categoria">
			                <td colspan="4">PRÉ RESERVA</td>
		                </tr>
			            <tr class="pacotes-tabela__tr" itemscope="" itemtype="http://schema.org/Event">
	                        <td class="td--data"></td>
                            <td class="td--evento">
                                <header class="pacotes-tabela__header">
                                    <a itemprop="url" href="https://www.amplitur.com/prereserva/984/rock-fest-barcelona-2021">
                                        <h2 itemprop="name" content="Excursão Rock Fest Barcelona 2021" class="pacotes-tabela__titulo">Rock Fest Barcelona 2021</h2>
                                        <span itemprop="location" itemscope="" itemtype="http://schema.org/Place" class="pacotes-tabela__subnome">
                                            <span itemprop="name"></span>
                                            <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">–
                                                <span itemprop="addressLocality">Barcelona</span>,
                                                <span itemprop="addressCountry">Espanha</span>
                                            </span>
                                        </span>
                                        <span itemprop="performer" itemscope="" itemtype="http://schema.org/Person">
                                        <meta itemprop="name" content="Rock Fest Barcelona 2021"></span>
                                        <meta itemprop="description" content="Rock Fest Barcelona 2021">
                                    </a>
                                </header>
                            </td>
                            <td class="td--icone"></td>
                            <td itemprop="offers" itemscope="" itemtype="http://schema.org/AggregateOffer" class="td--botao" style="height: 48px;">
                                <meta itemprop="url" content="https://www.amplitur.com/prereserva/984/rock-fest-barcelona-2021" />
                                <meta itemprop="validFrom" content="0000-00-00" />
                                <meta itemprop="availability" content="InStock" />
                                <a href="https://www.amplitur.com/prereserva/984/rock-fest-barcelona-2021" class="pacotes-lista__botao botao botao--pre-reservar--alt">Pré-reserva</a>
                            </td>
                        </tr>
			        </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
