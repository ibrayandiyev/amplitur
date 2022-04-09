$(document).ready(function () {

    $(".pricing-tab").on("click", function(){
        refreshCalculatePrices();
    });
    function refreshCalculatePrices(){
        $("[data-bookable-price]").each(function(i,e){
            calculateBookableReceivePrice($(this));
        });
    }
    // when start
    refreshCalculatePrices();
    function calculateBookableReceivePrice(target){
        let row = target.parent().parent().parent();
        let price = target.val();
        if(price === undefined || price == ""){
            price = "0";
        }
        let receivePriceSpan = row.find('[data-bookable-receive-price]');
        let saleCoefficient = $('[name="sale_coefficient_id"] option:selected').attr('data-coefficient');
        if(saleCoefficient == undefined){
            saleCoefficient = $('[name="sale_coefficient_id"]').attr('data-coefficient');
        }
        let salePrice = 0;

        price = price.replaceAll('.', '').replace(',', '.');
        price = parseFloat(price);

        saleCoefficient = parseFloat(saleCoefficient);
        salePrice = price * saleCoefficient;
        receivePriceSpan.text(toMoney(salePrice));
    }
    $('[data-bookable-price]').on('keyup', function (e) {
        let target = $(e.target);
        calculateBookableReceivePrice(target);
    });

    function toMoney(value) {
        if (value == NaN) {
            return '0,00';
        }

        return value.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL',
        }).replace('R$', '');
    }
});
