
<div class="mensagem--erro-fatorpass" data-original="{{ __('frontend.reservas.escolha_pacote_fator_pass') }} " style="display: none;"></div>

@foreach ($products as $key => $product)
    <div class="form__checkbox form__checkbox--radio servico-principal servico-principal__grupo-{{ $offer->type }} servico-principal__fatorpass-{{ $product->getCapacity() }} servico--disponivel" id="servico-principal-{{ $key }}">
        <input class="rd-servico-principal-produto validate-passengers" type="radio" name="servprinproduto" data-product="{{ $product->id }}" data-fatorpass="{{ $product->getCapacity() }}" value="{{ $product->id }}" 
        id="rd-servico-principal-produto-{{ $key }}" />
        <label for="rd-servico-principal-produto-{{ $key }}">
            <span class="servico-principal__descricao">
                <span class="servico-principal__nome">{{ __('frontend.pacotes.acomodation') }} {{ ($product->getTitle()) }}</span>
                <span class="servico-principal__a-partir-valor">
                    <span class="servico-principal__a-partir">{{ __('frontend.pacotes.a_partir') }}</span>
                    <strong class="servico-principal__valor">{{ money($product->getLowestPrice(), currency(), $offer->currency) }}</strong>
                    {{ __('frontend.pacotes.por_pessoa') }}
                </span>
            </span>
        </label>
    </div>
@endforeach

<script type="text/javascript">
    var productRadios = $('.rd-servico-principal-produto');


    productRadios.click(function (e) {
        let product = $(this);
        bookingRules.routeBookableProducts = '{{ route(getRouteByLanguage('frontend.ajax.bookable-products')) }}';
        var group = '{{ $offer->type }}';
        var packageId = '{{ $package->id }}';
        bookingRules.processBookableProducts(product, group, packageId);
    });
</script>
