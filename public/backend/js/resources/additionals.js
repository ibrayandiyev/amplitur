let availabilitySelect = $('select[name="availability"]');
let allowedProvidersRow = $('.allowed_providers_row');
let allowedCompaniesRow = $('.allowed_companies_row');

availabilitySelect.on('change', function (e) {
    handleAllowedCompanysRow(availabilitySelect.val());
    handleAllowedProvidersRow($(this).val());
});

function handleAllowedProvidersRow(availability) {
    if (availability == 'exclusive') {
        allowedProvidersRow.show();
    } else {
        allowedProvidersRow.hide();
    }
}

function handleAllowedCompanysRow(availability) {
    if (availability == 'exclusive') {
        allowedCompaniesRow.show();
    } else {
        allowedCompaniesRow.hide();
    }
}