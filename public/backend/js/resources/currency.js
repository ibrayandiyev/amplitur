
/**
 * Handle currency change
 *
 * @param  currency
 */
function handleDisplayCurrency(currency) {
    let fields = $('[data-display-currency]');
    let visibleField = $('[data-display-currency="' + currency + '"]');
    fields.hide();

    if (typeof visibleField !== undefined) {
        visibleField.show();
    }
}

$('[data-currency-selector]').change(function (e) {
    let currency = $(e.target).val();
    handleDisplayCurrency(currency);
});