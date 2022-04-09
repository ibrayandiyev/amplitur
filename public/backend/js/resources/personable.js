const selectCountryInput = $('[name="address[country]"],[name="bookingClient[address_country]"]');
const selectStateInput = $('[name="address[state]"],[name="bookingClient[address_state]"]');
const selectCityInput = $('[name="address[city]"]');
const selectPrimaryDocumentInput = $('[name="primary_document"]');
const selectUfInput = $('[name="uf"]');
const selectPersonTypeInput = $('[name="type"],[name="bookingClient[type]"]');
const textZipInput = $('[name="address[zip]"]');
const textRegistryInput = $('[name="registry"]');
const textDocumentInput = $('[name="document"], .document-input');
const textContactInput = $('[name="contacts[value][]"], [name="phone"], .phone-input').not('[type="email"]');
const textContactPhoneInput = $('.contact-flag').not('[type="email"]');
const textBirthdateInput = $('[name="birthdate"], .birthdate-input');
let brazilOnlyFields = $('[data-brazil-only]');

const inputMaskDate = '99/99/9999';
const inputMaskZip = '99.999-999';
const inputMaskRegistry = '99.999.999/9999-99';
const inputMaskDocument = '999.999.999-99';
const inputMaskContact = '(99) 999[9-]9999[9]';

selectCountryInput.change(onCountryChange);
selectPrimaryDocumentInput.change(onPrimaryDocumentChange);
selectPersonTypeInput.change(onPersonTypeChange);

textBirthdateInput.inputmask(inputMaskDate);

$(document).on('change', '[name="address[state]"]', onStateChange);

/**
 * Event called when person type change
 *
 * @return  {void}
 */
function onPersonTypeChange(e) {
    let notLegalFields = $('[not-legal]');
    let notFisicalFields = $('[not-fisical]');
    
    if (selectPersonTypeInput.val() == 'fisical') {
        notLegalFields.show();
        notFisicalFields.hide();
    } else {
        notLegalFields.hide();
        notFisicalFields.show();
    }

    onPrimaryDocumentChange(selectPersonTypeInput.val());
    if(e !== undefined)
        resetFieldsStates(1);
}

/**
 * Reset field that needs validation
 *
 * @return  {void}
 */
 function resetFieldsStates(level) {
     switch(level){
        case 1:
            selectCountryInput.find('option[value=""]').prop('selected', true).change();
            disableStateCity();
        case 2:
            if(selectPrimaryDocumentInput.attr("data-edit") == undefined){
                selectPrimaryDocumentInput.find('option[value=""]').prop('selected', true).change();
                selectUfInput.find('option[value=""]').prop('selected', true).change();
            }
            selectPrimaryDocumentInput.removeAttr("data-edit")
            break;
     }

}

/**
 * Event called when primary document change
 *
 * @return  {void}
 */
function onPrimaryDocumentChange(personType) {
    let withIdentityFields          = $('[data-identity-required]');
    let withIdentityBrazilFields    = $('[data-identity-required-brazil]');
    let withPassportFields = $('[data-passport-required]');
    let country             = selectCountryInput.find(":selected").val();

    if (personType == 'legal') {
        withIdentityFields.hide();
        withPassportFields.hide();
        return;
    }
        
    withIdentityBrazilFields.hide();
    if (selectPrimaryDocumentInput.find(":selected").val() == 'identity') {
        withIdentityFields.show();
        if(isBrazil(country)){
            withIdentityBrazilFields.show();
        }
        withPassportFields.hide();
    } else if (selectPrimaryDocumentInput.find(":selected").val() == 'passport') {
        withIdentityFields.hide();
        withPassportFields.show();
    } else {
        withIdentityFields.hide();
        withPassportFields.hide();
    }
}

/**
 * Event called when country change
 *
 * @param   {function}  callback
 * 
 * @return  {void}
 */
function onCountryChange(callback) {
    let country = selectCountryInput.find(":selected").val();
    resetFieldsStates(2);

    handleBrazilOnlyFields(country);
    handleRegistryMask(country);
    handleZipMask(country);
    disableStateCity();
    fetchStates(country, callback);
}

function refreshRegistryMask(){
    let country = selectCountryInput.find(":selected").val();
    handleRegistryMask(country);
}

/**
 * Event called when state change
 *
 * @param   {function}  callback
 *
 * @return  {void}
 */
function onStateChange(callback) {
    let country = selectCountryInput.val();
    let state = $('[name="address[state]"],[name="bookingClient[uf]"]').val();

    fetchCities(country, state, callback);
}

/**
 * Handle brazil-only fields display
 *
 * @param   {string}  country
 * 
 * @return  {void}
 */
function handleBrazilOnlyFields(country) {
    brazilOnlyFields = $('[data-brazil-only]');

    if (isBrazil(country)) {
        brazilOnlyFields.show();
    } else {
        brazilOnlyFields.hide();
    }
    if(selectPersonTypeInput.val() == "legal"){
        let notLegalFields = $('[not-legal]');
        notLegalFields.hide();
    }
    onPrimaryDocumentChange(selectPersonTypeInput.val());

}

/**
 * Handle contact country flag display
 *
 * @param   {string}  country
 *
 * @return  {void}
 */
function initContactCountryFlag() {
    let inputsLength = textContactInput.length;

    for (let i = 0; i < inputsLength; i++) {
        let iti = window.intlTelInput(textContactInput[i], {
            initialCountry: 'auto',
            separateDialCode: true,
            autoHideDialCode: false,
            preferredCountries: [
                'br',
                'de',
                'us',
            ]
        });

        let selectedCountry = iti.getSelectedCountryData().iso2;

        if (selectedCountry == 'br') {
            $(iti.a).inputmask(inputMaskContact);
        } else {
            $(iti.a).inputmask('remove');
            $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
        }

        textContactInput[i].addEventListener('countrychange', () => {
            let selectedCountry = iti.getSelectedCountryData().iso2;

            if (selectedCountry == 'br') {
                $(iti.a).inputmask(inputMaskContact);
            } else {
                $(iti.a).inputmask('remove');
                $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
            }
        });
    }
}

/**
 * Handle contact country flag display
 *
 * @param   {string}  country
 *
 * @return  {void}
 */
 function initContactPhoneCountryFlag() {
    let inputsLength = textContactPhoneInput.length;

    for (let i = 0; i < inputsLength; i++) {
        let iti = window.intlTelInput(textContactPhoneInput[i], {
            initialCountry: 'auto',
            separateDialCode: true,
            autoHideDialCode: false,
            preferredCountries: [
                'br',
                'de',
                'us',
            ]
        });

        let selectedCountry = iti.getSelectedCountryData().iso2;

        if (selectedCountry == 'br') {
            $(iti.a).inputmask(inputMaskContact);
        } else {
            $(iti.a).inputmask('remove');
            $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
        }

        textContactPhoneInput[i].addEventListener('countrychange', () => {
            let selectedCountry = iti.getSelectedCountryData().iso2;

            if (selectedCountry == 'br') {
                $(iti.a).inputmask(inputMaskContact);
            } else {
                $(iti.a).inputmask('remove');
                $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
            }
        });
    }
}

/**
 * Handle document input mask
 *
 * @return  {void}
 */
function handleDocumentMask() {
    textDocumentInput.inputmask(inputMaskDocument);
}

/**
 * Handle registry input mask
 *
 * @param   {string}  country
 *
 * @return  {void}
 */
function handleRegistryMask(country) {
    if (!isBrazil(country)) {
        textRegistryInput.inputmask('remove');
        if (textRegistryInput.val() !== undefined) {
            textRegistryInput.val(textRegistryInput.val().replace(/[._/-]*/g, ''));
        }
    } else {
        if (textRegistryInput.val() !== undefined) {
            textRegistryInput.val(textRegistryInput.val().replace(/\D/g, ''));
        }
        textRegistryInput.inputmask(inputMaskRegistry);
    }
}

/**
 * Handle zip input mask
 *
 * @param   {string}  country
 *
 * @return  {void}
 */
function handleZipMask(country) {
    if (!isBrazil(country)) {
        textZipInput.inputmask('remove');
        if (textZipInput.val() !== undefined) {
            textZipInput.val(textZipInput.val().replace(/\D/g, ''));
        }
    } else {
        textZipInput.inputmask(inputMaskZip);
    }
}

/**
 * Handle primary document change
 *
 * @param   {string}  country
 *
 * @return  {void}
 */
function handlePrimaryDocumentChange(object) {
    var primaryDocumentSelect = $("."+object+"_pd");
    var fieldShow = primaryDocumentSelect.find(":selected").attr("data-field");
    $("."+object+"_primary_document").hide();
    $("."+fieldShow).show();

}

/**
 * Handle State
 *
 * @return  {void}
 */
function handleState() {
    
}

/**
 * Populate a select input with states
 *
 * @param   {object}  data
 *
 * @return  {void}
 */
function populateStateSelect(data) {
    let tag = '<select class="form-control" name="address[state]" disabled>';
    let region = '[data-state-region]';
    let select = $('[name="address[state]"],[name="bookingClient[address_state]"]');

    select.remove();
    select = $(tag).appendTo(region);
    select.empty();

    select.append($('<option value>-- Selecione --</option>'));

    $(data).each((i, state) => {
        select.append($('<option>').attr('value', state.iso2).text(state.name));
    });

    // Reset and disable city
    $('[name="address[city]"]').val('');
    $('[name="address[city]"]').attr('disabled', true);

    select.removeAttr('disabled');
}

/**
 * Populate a select input with cities
 *
 * @param   {object}  data
 *
 * @return  {void}
 */
function populateCitySelect(data) {
    let tag = '<select class="form-control" name="address[city]" disabled>';
    let region = '[data-city-region]';
    let select = $('[name="address[city]"],[name="bookingClient[address_city]"]');
    
    select.remove();
    select = $(tag).appendTo(region);
    select.empty();

    select.append($('<option value>-- Selecione --</option>'));

    $(data).each((i, city) => {
        select.append($('<option>').attr('value', city.id).text(city.name));
    });

    select.removeAttr('disabled');
}

/**
 * Create a text input to state
 *
 * @return  {void}
 */
function createStateInput() {
    let tag = '<input type="text" class="form-control text-uppercase" name="address[state]">';
    let region = '[data-state-region]';
    let input = $('[name="address[state]"]');

    input.remove();
    input = $(tag).appendTo(region);
}

/**
 * Create a text input to city
 *
 * @return  {void}
 */
function createCityInput() {
    let tag = '<input type="text" class="form-control text-uppercase" name="address[city]">';
    let region = '[data-city-region]';
    let input = $('[name="address[city]"]');

    input.remove();
    input = $(tag).appendTo(region);
}

/**
 * Fetch states calling API
 *
 * @param   {string}    country
 * @param   {function}  callback
 *
 * @return  {void}
 */
function fetchStates(country, callback) {
    $.get(`/api/countries/${country}/states`, (response) => {
        populateStateSelect(response);
    }).fail(() => {
        createStateInput();
        createCityInput();
    }).always(() => {
        if (callback && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Fetch cities calling API
 *
 * @param   {string}    country
 * @param   {string}    state
 * @param   {function}  callback
 *
 * @return  {void}
 */
function fetchCities(country, state, callback) {
    $.get(`/api/countries/${country}/states/${state}/cities`, (response) => {
        populateCitySelect(response);
    }).fail(() => {
        createCityInput();
    }).always(() => {
        if (callback && typeof callback === 'function') {
            callback();
        }
    });
}

/**
 * Fill preformed address fields
 *
 * @param   {object}  address
 *
 * @return  {void}
 */
function fillAddress(address, disabled = false) {
    onCountryChange(() => {
        $('[name="address[state]"]').val(address.state);
        
        if (disabled) {
            $('[name="address[state]"]').attr('disabled', true);
        }

        onStateChange(() => {
            $('[name="address[city]"]').val(address.city);

            if (disabled) {
                $('[name="address[city]"]').attr('disabled', true);
            }
        });
    });
}

/**
 * Disable state and city inputs
 *
 * @return  {void}
 */
function disableStateCity() {
    $('[name="address[state]"]').attr('disabled');
    $('[name="address[city]"]').attr('disabled');
}

/**
 * Check if country is Brazil
 *
 * @param   {string}  country
 * 
 * @return  {boolean}
 */
function isBrazil(country) {
    return country === 'BR';
}

/**
 * Check if input has input-mask
 *
 * @param   {object}  input
 * 
 * @return  {boolean}
 */
function hasMask(input) {
    return input.inputmask !== undefined && typeof input.inputmask !== 'function';
}

function catchFormSubmit(form) {
    form[0].addEventListener('submit', sanitizePhoneInputs);
}

function sanitizePhoneInputs(e) {
    let phoneInputs = $('.phone-flag');
    let length = phoneInputs.length;

    for (let i = 0; i < length; i++) {

        let input = $(phoneInputs[i]);
        let countryPrefix = input.parent().find('.iti__selected-dial-code').text();

        if (input.val()) {
            let value = input.val();
            value = value.replace(/\D/g, '');
            value = countryPrefix + '' + value;
            phoneInputs.inputmask('remove');
            input.val(value);
        }
    }
}

$(document).ready(() => {
    onPrimaryDocumentChange();
    handleDocumentMask();
    handleBrazilOnlyFields();
    onPersonTypeChange();
    initContactCountryFlag();
    initContactPhoneCountryFlag();
})