@extends('frontend.template.default')

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site2 ma">
            <div class="corpo-texto">
                <h2 class="pagina__subtitulo" style="margin-top: 50px">Internet Banking</h2>
                <p>Chamando Interface com o Banco Itau &ndash; Seu pop-up deve estar habilitado e esta janela n&atilde;o pode ser fechada. Aguarde...</p>

                <form action="https://shopline.itau.com.br/shopline/shopline.aspx" method="post" name="form1" onsubmit="carregabrw()" target="SHOPLINE">
                    <input type="hidden" name="DC" value="{{ $hash }}">
                
                    <div class="a-centro">
                        <button class="botao botao--padrao">Clique aqui para prosseguir ou tentar novamente</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
	<script>
		function carregabrw() {
			window.open("", "SHOPLINE", "toolbar=yes,menubar=yes,resizable=yes,status=no,scrollbars=yes,width=815,height=575");
		}
		document.form1.submit();
	</script>
@endpush