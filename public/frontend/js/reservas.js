// Gerenciamento de processo de reservas
// guilhermemuller.com.br
// contato@guilhermemuller.com.br

(function () {

    // variáveis base
    var pais = $('.pais').val();
    var totalGeral = 0;
    var descontoPromo = 0;
    var finalizar = false;
    var ravValor = 0;

    // jquery objs
    var $rav = $('#rav');

    /**
     * Eventos
     */

    $('body').on('change', '.sl-documento', function () {
        checkDocumento($(this));
    });

    $rav.blur(function () {
        var valor = $(this).val();

        if (isNaN(valor)) {
            valor = 0;
        }

        ravValor = valor;

        checkTotal();
    })

    /*
    $('.rd-servico-adicional').click(function() {
        checkTotal();
    });
    */

    $('.botao--processar').click(function (event) {
        if (finalizar) {
            event.preventDefault();
            return false;
        }

        $(this).removeClass('botao--comprar');
        $(this).addClass('botao--processando');
        $(this).html("<i class='fas fa-pulse fa-spinner'></i> Processando...");

        // temp
        //event.preventDefault();
        //return false;

        finalizar = true;
    });

    /**
     * Funções
     */

    function checkAllDocumentos() {
        $('.sl-documento').each(function () {
            checkDocumento($(this));
        });
    }

    function checkDocumento($jqobj) {
        var valor = $jqobj.val();
        var pass = $jqobj.attr('data-pass');

        if (pais == 30) {
            $('.rg-' + pass + ' .pass-cpf').attr("required", "true");
        }

        if (valor == 'passaporte') {
            togglePass(pass);
        } else if (valor == 'certidao') {
            toggleCertidao(pass);
        } else {
            toggleRG(pass);
        }
    }

    function toggleRG(pass) {
        $('.rg-' + pass).show();
        $('.cert-' + pass).hide();
        $('.ps-' + pass).hide();

        if (pais != 30) {
            $('.nacional').hide();
        }
    }

    function togglePass(pass) {
        $('.rg-' + pass).hide();
        $('.cert-' + pass).hide();
        $('.ps-' + pass).show();
        $('.rg-' + pass + ' .pass-cpf').removeAttr("required");
    }

    function toggleCertidao(pass) {
        $('.rg-' + pass).hide();
        $('.cert-' + pass).show();
        $('.ps-' + pass).hide();
        $('.rg-' + pass + ' .pass-cpf').removeAttr("required");
    }

    function checkTotal() {
        totalGeral = 0;

        // principal
        var $servicoPrincipal = $('#servprin');
        var servicoPrincipalValor = parseFloat($servicoPrincipal.data('valor')) * parseInt($servicoPrincipal.data('quantidade'));

        totalGeral += servicoPrincipalValor += ravValor;

        // adicionais
        $('.rd-servico-adicional').each(function () {
            if ($(this).prop('checked')) {
                var valor = parseFloat($(this).data('valor'));

                if (!isNaN(valor)) {
                    totalGeral += valor;
                }
            }
        });

        // promocode geral
        if (descontoPromo) {
            totalGeral -= descontoPromo;
        }

        // Patch para ajustar quando o Promocode for maior que o pacote, evitar que fique negativo. Rods 17082016
        if (totalGeral <= 0) { totalGeral = 0; }
        // Fim Patch

        var valorFinal = "R$ " + formatNum(totalGeral);
        var valorTaxaServico = $(".taxa-servico-campo").html();

        $('.valor-passageiro__valor').text(valorFinal);
        $('.valor-passageiro__taxa-servico').html(valorTaxaServico);

        // Mostrando em outras cotações
        var cotacaoUsd = $('#cotacao-usd');
        if (cotacaoUsd.length != 0) {
            var valorUsd = totalGeral / cotacaoUsd.attr("data-valor");
            cotacaoUsd.html(cotacaoUsd.attr("data-simbolo") + " " + formatNum(valorUsd));
        }
        var cotacaoEur = $('#cotacao-eur');
        if (cotacaoEur.length != 0) {
            var valorEur = totalGeral / cotacaoEur.attr("data-valor");
            cotacaoEur.html(cotacaoEur.attr("data-simbolo") + " " + formatNum(valorEur));
        }
        var cotacaoGeral = $('#cotacao-geral');
        if (cotacaoGeral.length != 0) {
            var valorGeral = totalGeral * cotacaoGeral.attr("data-valor");
            cotacaoGeral.html(cotacaoGeral.attr("data-simbolo") + " " + formatNum(valorGeral));
        }
    }

    function replicaTelefonePassageiro() {
        $(document).ready(function () {
            $(".change-phone-intlddi").change(function () {
                var i = $(this).attr("data-passageiro");
                var ddi = $(this).children("option:selected").attr("data-ddi");
                $("#passageiro2_" + i + "_fone").val(ddi + " " + $("#passageiro_" + i + "_fone").val());
                $("#passageiro_" + i + "_fone").focus();
            });
            $(".pass-fone").change(function () {
                var i = $(this).attr("data-passageiro");
                var ddi = $("#sl-ddi" + i + " option:selected").attr("data-ddi");
                console.log("#passageiro2_" + i + "_fone");
                $("#passageiro2_" + i + "_fone").val(ddi + " " + $(this).val());
            });
        });

    }

    /**
     * Inicialização
     */

    function init() {
        checkAllDocumentos();
        checkTotal();
        replicaTelefonePassageiro();

        // fixa resumo
        create_sticky($('#resumo'), $('#resumo-ph'), true);
    }

    init();

})();