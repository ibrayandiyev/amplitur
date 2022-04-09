<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher Promocode</title>

    <STYLE type=text/css>

        .tablevoucher {
        font-family: Arial, Helvetica, sans-serif;
        border: 4px solid #0b4aa8;
        width: 100%;
        text-align: center;
        }

        .tdvoucherhead {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 24px;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
        background-color: #0b4aa8;
        border-collapse: collapse;
        }

        .fonthead{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        font-weight: bold;
        text-align: left;
        padding: 0.1em;
        }

        .fonthead_inform{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        font-weight: bold;
        text-align: left;
        padding: 0.2em;
        border-collapse: collapse;

        }

        .borderleft{
            border-left-width: 3px;
            border-left-color: #0b4aa8;
            border-left-style: solid;
        }

        .borderright{
            border-right-width: 3px;
            border-right-color: #0b4aa8;
            border-right-style: solid;

        }

        .borderbase{
            border-bottom-width: 3px;
            border-bottom-color: #0b4aa8;
            border-bottom-style: solid;

        }

        .ptable {

            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000000;
            text-align:justify;
            padding: 0.1em;
        }


        </style>

<body>

    <table class="tablevoucher">

        <tr>
            <td rowspan=5 width=25%>
                <img src="https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png" alt="homepage" style="padding: 0.3em" />
            </td>
            <td colspan=10 class="tdvoucherhead">VOUCHER DE CRÉDITO  ---> {{$promocode->id}}
            </td>
        </tr>
        <tr>
            <td colspan=2 class="fonthead borderleft" >Nome:  {{ __('frontend.reservas.gerar_boleto')}}
            </td>
            <td colspan=8 class="fonthead_inform borderright">nome do titular
            </td>
        </tr>
        <tr class="trtable">
            <td colspan=2 class="fonthead borderleft">Valor:
            </td>
            <td colspan=8 class="fonthead_inform borderright">##Valor por extenso e numeral##
            </td>
        </tr>
        <tr>
            <td colspan=2 class="fonthead borderleft">Código:
            </td>
            <td colspan=8 class="fonthead_inform borderright" style="color: #107220">Código do desconto
            </td>
        </tr>
        <tr>
            <td colspan=2 class="fonthead borderleft borderbase ">Validade:
            </td>
            <td colspan=8 class="fonthead_inform borderright borderbase" style="color: #a80000">##ValidadeVali dadeValidad eValidad eValidadeValidadeVali dadeValidadeValidade##
            </td>
        </tr>
        <tr>
            <td colspan=11>
<hr style="color: #0b4aa8;">
                <p class="ptable">
                <strong>INSTRUÇÕES:</strong>
                </P>
                <p class="ptable">
                    Para aproveitar seu desconto, basta inserir o código no campo “PROMOCODE/CUPOM DE DESCONTO” na página do pacote escolhido durante o processo de compra.
                <ul>
                    <li class="ptable">O cupom é pessoal e intransferível;</li>
                    <li class="ptable">O desconto está vinculado ao nome do beneficiário como consta no cadastro, portanto, só terá validade se for utilizado em uma compra efetuada com login e senha do ganhador;</li>
                    <li class="ptable">Desconto válido somente para compras feitas no site www.amplitur.com.</li>
                </ul>
                <p>
            </td>
        </tr>




     </table>
</body>
</html>
