const selectCountryInput = $('[name="address[country]"]');
const selectStateInput = $('[name="address[state]"]');
const selectCityInput = $('[name="address[city]"]');

selectCountryInput.change(onCountryChange);

$(document).on('change', '[name="address[state]"]', onStateChange);

/**
 * Event called when country change
 *
 * @param   {function}  callback
 * 
 * @return  {void}
 */
function onCountryChange(callback) {
    let country = selectCountryInput.val();

    disableStateCity();
    handleZipMask(country);
    fetchStates(country, callback);
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
    let state = $('[name="address[state]"]').val();

    fetchCities(country, state, callback);
}

function handleZipMask(country) {
    const textZipInput = $('[name="address[zip]"]');
    const inputMaskZip = '99.999-999';
    
    if (country != 'BR') {
        textZipInput.inputmask('remove');
    } else {
        textZipInput.inputmask(inputMaskZip);
    }
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
    let select = $('[name="address[state]"]');

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
    let select = $('[name="address[city]"]');

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
 * Disable state and city inputs
 *
 * @return  {void}
 */
function disableStateCity() {
    $('[name="address[state]"]').attr('disabled');
    $('[name="address[city]"]').attr('disabled');
}

/**
 * Fill preformed address fields
 *
 * @param   {object}  address
 *
 * @return  {void}
 */
function fillAddress(address, disabled = false) {
    if (address.country) {
        onCountryChange(() => {
            $('[name="address[state]"').val(address.state);

            if (disabled) {
                $('[name="address[state]"').attr('disabled', true);
            }

            onStateChange(() => {
                $('[name="address[city]"').val(address.city);

                if (disabled) {
                    $('[name="address[city]"').attr('disabled', true);
                }
            });
        });
    }
}