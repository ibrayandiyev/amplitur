@extends('frontend.template.page')

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site2 ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{__('frontend.conta.cadastre_se')}}</h1>
            </header>

            <div class="corpo-texto">
	            <h2>{{__('frontend.misc.cadastro_efetuado')}}</h2>
            </div>

            <p><strong>{{__('frontend.misc.cadastro_efetuado_msg1')}}{{ $email }}{{__('frontend.misc.cadastro_efetuado_msg1_1')}}</strong></p>
            <p>
                {{__('frontend.misc.cadastro_efetuado_msg2')}}
            </p>

            <p>
                {{__('frontend.misc.cadastro_efetuado_msg3')}}
            </p>
        </div>
    </main>
@endsection
