/**
 * Google Maps instance
 *
 * @var {Map}
 */
let map;

/**
 * Initial map center
 *
 * @var {object}
 */
let defaultLocation = { lat: -34.397, lng: 150.644 };

/**
 * Loaded markers on the map
 *
 * @var {array}
 */
let markers = [];

let inputLocation = $(document).find('[data-map-location]');
let inputAddress = $(document).find('[name="address[address]"]');
let inputNumber = $(document).find('[name="address[number]"]');
let inputZip = $(document).find('[name="address[zip]"]');
let inputNeighborhood = $(document).find('[name="address[neighborhood]"]');
let inputCity = $(document).find('[name="address[city]"]');
let inputState = $(document).find('[name="address[state]"]');
let inputCountry = $(document).find('[name="address[country]"]');
let inputLatitute = $(document).find('[name="address[latitude]"]');
let inputLongitude = $(document).find('[name="address[longitude]"]');

inputLocation.keyup(handleSearchLocation);
inputAddress.keyup(handleSearchLocation);
inputNumber.keyup(handleSearchLocation);
inputCountry.change(handleSearchLocation);
$(document).on('change keyup', '[name="address[state]"]', handleSearchLocation);
$(document).on('change keyup', '[name="address[city]"]', handleSearchLocation);

/**
 * Init google map instance
 *
 * @return  {void}
 */
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLocation,
        zoom: 8,
        disableDefaultUI: true,
        zoomControl: true,
        fullscreenControl: true,
    });

    createMarker(getCurrentLocation());
}

/**
 * Called when marker is dragged
 *
 * @return  {void}
 */
function handleMarkerDragged(markerIndex, draggedMarker)
{
    let latitude = draggedMarker.latLng.lat();
    let longitude = draggedMarker.latLng.lng();

    setLatitudeLongitude(latitude, longitude);
}

/**
 * Set a map to a marker
 *
 * @param   {integer}  markerIndex
 * @param   {google.maps.Map}  targetMap
 *
 * @return  {void}
 */
function setMapOnMarker(markerIndex, targetMap) {
    if (targetMap === undefined)  {
        targetMap = map;
    }

    markers[markerIndex].setMap(targetMap);
}

/**
 * Clear markers from map
 *
 * @return  {void}
 */
function clearMarkers() {
    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
}

/**
 * Create a new marker on the map
 *
 * @param   {LatLng}  position
 * @param   {boolean}  draggable
 *
 * @return  {void}
 */
function createMarker(position, draggable = true)
{
    let marker = new google.maps.Marker({
        position: position,
        map: map,
        draggable: draggable,
    });

    marker.addListener("dragend", (draggedMarker) => {
        handleMarkerDragged(markers.length - 1, draggedMarker);
    });

    markers.push(marker);

    map.setCenter(position);
    map.setZoom(18);

    setLatitudeLongitude(position.lat, position.lng);
}

function setLatitudeLongitude(latitude, longitude)
{
    inputLatitute.val(latitude);
    inputLongitude.val(longitude);
}

/**
 * Call Geocoding API to get information based on address
 *
 * @param   {string}  address
 *
 * @return  {void}
 */
function getAddressPosition(address)
{
    let key = $('meta[name="google-maps-key"]').attr('content');
    let url = `https://maps.googleapis.com/maps/api/geocode/json?key=${key}&address="${address}"`;

    $.get(url, (response) => {
        if (response.status == "ZERO_RESULTS") {
            return;
        }

        let result = response.results[response.results.length - 1];
        let position = result.geometry.location;

        deleteMarkers();
        createMarker(position, true);
    });
}

/**
 * Get latitude and lontitude of current location
 *
 * @return  {object}
 */
function getCurrentLocation()
{   
    return {
        lat: parseFloat(inputLatitute.val()),
        lng: parseFloat(inputLongitude.val()),
    };
}

/**
 * Get an address composite string using all address inputs
 *
 * @return  {string}
 */
function getCompositeAddress()
{
    let compositeAddress = '';
    inputCity = $(document).find('[name="address[city]"]');
    inputState = $(document).find('[name="address[state]"]');

    if (inputLocation.val() != undefined && inputLocation.val() != '') {
        compositeAddress += `+${inputLocation.val()}`;
    }

    if (inputAddress.val() != undefined && inputAddress.val() != '') {
        compositeAddress += `+${inputAddress.val()}`;
    }

    if (inputNumber.val() != undefined && inputNumber.val() != '') {
        compositeAddress += `+${inputNumber.val()}`;
    }
    
    if (inputCity.is('select')) {
        if (inputCity.find('option:selected').text() != undefined && inputCity.find('option:selected').text() != '') {
            compositeAddress += `+${inputCity.find('option:selected').text()}`;
        }
    } else {
        if (inputCity.val() != undefined && inputCity.val() != '') {
            compositeAddress += `+${inputCity.val()}`;
        }
    }

    if (inputState.is('select')) {
        if (inputState.find('option:selected').text() != undefined && inputState.find('option:selected').text() != '') {
            compositeAddress += `+${inputState.find('option:selected').text()}`;
        }
    } else {
        if (inputState.val() != undefined && inputState.val() != '') {
            compositeAddress += `+${inputState.val()}`;
        }
    }

    if (inputCountry.val() != undefined && inputCountry.val() != '') {
        compositeAddress += `+${inputCountry.val()}`;
    }

    return parseString(compositeAddress);
}

/**
 * Parse string replacing whitespaces into plus signal
 *
 * @return  {string}
 */
function parseString(string)
{
    return string.replace(/\s/g, '+');
}

/**
 * Delete all markers from map and array
 *
 * @return  {void}
 */
function deleteMarkers()
{
    clearMarkers()
    markers = [];
}

/**
 * Handle search using debounce
 *
 * @return  {void}
 */
function handleSearchLocation() {
    clearTimeout($.data(this, 'timer'));

    $('.map-container').css('opacity', 0.5);

    var wait = setTimeout(() => {
        getAddressPosition(getCompositeAddress());
        $('.map-container').css('opacity', 1);
    }, 1000);

    $(this).data('timer', wait);
};
