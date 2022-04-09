<?php

use App\Models\Promocode;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Back-end Routes
|--------------------------------------------------------------------------
|
| Here is where you can register back-end routes
|
*/
//Gerador de PDF
Route::get('gerapdf/promocode/{promocode}', 'GeneratePdfController@gerarpromocode')->name('backend.gerarpdf.promocode');

//Cadastro de Providers

Route::get('providers/cadastro', 'ProviderController@register')->name('backend.providers.register');
Route::post('providers/cadastro', 'ProviderController@doRegister')->name('backend.providers.doRegister');
Route::get('providers/validate-token/{validation_token}', 'ProviderController@validateToken')->name('backend.providers.validateToken');
// Lanuage
Route::get('language/{language}', 'LanguageController@change')->name('backend.language.change');

Route::middleware(['admin', 'auth:users,providers'])->group(function () {

    // Dashboard
    Route::get('/', 'HomeController@index')->name('backend.index');

    // Clients
    Route::group(['prefix' => 'clientes'], function () {
        Route::get('/', 'ClientsController@index')->name('backend.clients.index');
        Route::get('/novo', 'ClientsController@create')->name('backend.clients.create');
        Route::post('/novo', 'ClientsController@store')->name('backend.clients.store');
        Route::get('/filtrar', 'ClientsController@filter')->name('backend.clients.filter');
        Route::get('/exportar', 'ClientsController@export')->name('backend.clients.export');
        Route::get('/loginAsCustomer/{client}', 'ClientsController@loginAsCustomer')->name('backend.clients.loginAsCustomer');
        Route::post('/{client}/historico', 'ClientsController@storeLog')->name('backend.clients.storeLog');
        Route::post('/{client}/historico/deletar', 'ClientsController@destroyLog')->name('backend.clients.deleteLog');
        Route::get('/{client}', 'ClientsController@edit')->name('backend.clients.edit');
        Route::put('/{client}', 'ClientsController@update')->name('backend.clients.update');
        Route::delete('/{client}/deletar', 'ClientsController@destroy')->name('backend.clients.destroy');
    });

    // Events
    Route::group(['prefix' => 'eventos'], function () {
        Route::get('/', 'EventController@index')->name('backend.events.index');
        Route::get('/novo', 'EventController@create')->name('backend.events.create');
        Route::post('/novo', 'EventController@store')->name('backend.events.store');
        Route::post('/filtrar', 'EventController@filter')->name('backend.events.filter');
        Route::get('/filtrar', 'EventController@index');
        Route::post('/filtrar/json', 'EventController@jsonFilter')->name('backend.events.jsonFilter');
        Route::get('/exportar', 'EventController@export')->name('backend.events.export');
        Route::post('/importar', 'EventController@import')->name('backend.events.import');
        Route::get('/{event}', 'EventController@edit')->name('backend.events.edit');
        Route::put('/{event}', 'EventController@update')->name('backend.events.update');
        Route::delete('/{event}/deletar', 'EventController@destroy')->name('backend.events.destroy');
        Route::get('/{event}/replicar', 'EventController@replicate')->name('backend.events.replicate');
        Route::get('/{event}/event_change_notification', 'EventController@eventChangeNotification')->name('backend.events.event_change_notification');
    });

    // Hotels
    Route::resource('hotels', 'HotelsController'            , ['as' => 'backend', 'parameters' => [ 'hotel'                 => 'hotel']]);
    Route::get('/teste/hotels'                                , 'HotelsController@datatable')->name('backend.hotels.datable');

    // Import
    Route::get('/imports/import_clients'    , 'Imports\ImportsController@import_clients')->name('backend.imports.import_clients');
    Route::get('/imports/import_reservation', 'Imports\ImportsController@import_reservation')->name('backend.imports.import_reservation');

    // Categories
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('/', 'CategoryController@index')->name('backend.categories.index');
        Route::get('/novo', 'CategoryController@create')->name('backend.categories.create');
        Route::post('/novo', 'CategoryController@store')->name('backend.categories.store');
        Route::get('/{category}', 'CategoryController@edit')->name('backend.categories.edit');
        Route::put('/{category}', 'CategoryController@update')->name('backend.categories.update');
        Route::delete('/{category}/deletar', 'CategoryController@destroy')->name('backend.categories.destroy');
    });

    // Providers
    Route::group(['prefix' => 'providers'], function () {
        Route::get('/', 'ProviderController@index')->name('backend.providers.index');
        Route::get('/novo', 'ProviderController@create')->name('backend.providers.create');
        Route::post('/novo', 'ProviderController@store')->name('backend.providers.store');
        Route::post('/filtrar', 'ProviderController@filter')->name('backend.providers.filter');
        Route::get('/filtrar', 'ProviderController@index');
        Route::get('/{provider}', 'ProviderController@edit')->name('backend.providers.edit');
        Route::put('/{provider}', 'ProviderController@update')->name('backend.providers.update');
        Route::post('/{provider}/historico', 'ProviderController@storeLog')->name('backend.providers.storeLog');
        Route::post('/{provider}/historico/deletar', 'ProviderController@destroyLog')->name('backend.providers.deleteLog');
        Route::delete('/{provider}/deletar', 'ProviderController@destroy')->name('backend.providers.destroy');


        // Companies
        Route::get('/{provider}/empresas', 'CompanyController@index')->name('backend.providers.companies.index');
        Route::get('/{provider}/empresas/novo', 'CompanyController@create')->name('backend.providers.companies.create');
        Route::post('/{provider}/empresas/novo', 'CompanyController@store')->name('backend.providers.companies.store');
        Route::get('/{provider}/empresas/{company}', 'CompanyController@edit')->name('backend.providers.companies.edit');
        Route::put('/{provider}/empresas/{company}', 'CompanyController@update')->name('backend.providers.companies.update');
        Route::delete('/{provider}/empresas/{company}/deletar', 'CompanyController@destroy')->name('backend.providers.companies.destroy');

        // Companies - Documents
        Route::get('/{provider}/empresas/{company}/documentos/{document}/aprovar', 'CompanyDocumentController@accept')->name('backend.providers.companies.documents.accept');
        Route::get('/{provider}/empresas/{company}/documentos/{document}/reprovar', 'CompanyDocumentController@decline')->name('backend.providers.companies.documents.decline');
        Route::delete('/{provider}/empresas/{company}/documentos/{document}/excluir', 'CompanyDocumentController@destroy')->name('backend.providers.companies.documents.destroy');
        Route::get('/{provider}/empresas/{company}/documentos/{document}/em-analise', 'CompanyDocumentController@inAnalysis')->name('backend.providers.companies.documents.inAnalysis');
        Route::get('/{provider}/empresas/{company}/documentos/{document}/view', 'CompanyDocumentController@show')->name('backend.providers.companies.documents.show');

        // Packages
        Route::get('/{provider}/pacotes', 'ProviderPackagesController@index')->name('backend.providers.packages.index');
        Route::get('/{provider}/pacotes/novo', 'ProviderPackagesController@create')->name('backend.providers.packages.create');
        Route::post('/{provider}/pacotes/novo', 'ProviderPackagesController@store')->name('backend.providers.packages.store');
        Route::get('/{provider}/pacotes/{package}', 'ProviderPackagesController@edit')->name('backend.providers.packages.edit');
        Route::put('/{provider}/pacotes/{package}', 'ProviderPackagesController@update')->name('backend.providers.packages.update');
        Route::delete('/{provider}/pacotes/{package}/deletar', 'ProviderPackagesController@destroy')->name('backend.providers.packages.destroy');

        // Offers
        Route::get('/{provider}/empresas/{company}/ofertas', 'ProviderOffersController@index')->name('backend.providers.companies.offers.index');
        Route::get('/{provider}/empresas/{company}/ofertas/criar', 'ProviderOffersController@prepare')->name('backend.providers.companies.offers.prepare');
        Route::get('/{provider}/empresas/{company}/ofertas/nova', 'ProviderOffersController@create')->name('backend.providers.companies.offers.create');
        Route::post('/{provider}/empresas/{company}/ofertas/nova', 'ProviderOffersController@store')->name('backend.providers.companies.offers.store');
        Route::get('/{provider}/empresas/{company}/ofertas/{offer}', 'ProviderOffersController@edit')->name('backend.providers.companies.offers.edit');
        Route::put('/{provider}/empresas/{company}/ofertas/{offer}', 'ProviderOffersController@update')->name('backend.providers.companies.offers.update');
        Route::delete('/{provider}/empresas/{company}/ofertas/{offer}/deletar', 'ProviderOffersController@destroy')->name('backend.providers.companies.offers.destroy');

        // Offers Gallery
        Route::get('/{provider}/empresas/{company}/ofertas/{offer}/galeria/nova', 'ProviderOffersController@galleryCreateImage')->name('backend.providers.companies.offers.gallery.create');
        Route::post('/{provider}/empresas/{company}/ofertas/{offer}/galeria/nova', 'ProviderOffersController@galleryStoreImage')->name('backend.providers.companies.offers.gallery.store');
        Route::get('/{provider}/empresas/{company}/ofertas/{offer}/galeria/{image}', 'ProviderOffersController@galleryEditImage')->name('backend.providers.companies.offers.gallery.edit');
        Route::put('/{provider}/empresas/{company}/ofertas/{offer}/galeria/{image}', 'ProviderOffersController@galleryUpdateImage')->name('backend.providers.companies.offers.gallery.update');
        Route::delete('/{provider}/empresas/{company}/ofertas/{offer}/galeria/{image}/deletar', 'ProviderOffersController@galleryDestroyImage')->name('backend.providers.companies.offers.gallery.destroy');

        // Offers - BusTrip
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/nova', 'ProviderBustripOffersController@createRoute')->name('backend.providers.companies.offers.bustrip.createRoute');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/nova', 'ProviderBustripOffersController@storeRoute')->name('backend.providers.companies.offers.bustrip.storeRoute');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}', 'ProviderBustripOffersController@editRoute')->name('backend.providers.companies.offers.bustrip.editRoute');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}', 'ProviderBustripOffersController@updateRoute')->name('backend.providers.companies.offers.bustrip.updateRoute');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/deletar', 'ProviderBustripOffersController@destroyRoute')->name('backend.providers.companies.offers.bustrip.destroyRoute');

        // Offers - BusTrip - Boarding Locations
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/embarques/novo', 'ProviderBustripOffersController@createBoardingLocation')->name('backend.providers.companies.offers.bustrip.createBoardingLocation');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/embarques/novo', 'ProviderBustripOffersController@storeBoardingLocation')->name('backend.providers.companies.offers.bustrip.storeBoardingLocation');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/embarques/{bustripBoardingLocation}', 'ProviderBustripOffersController@editBoardingLocation')->name('backend.providers.companies.offers.bustrip.editBoardingLocation');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/embarques/{bustripBoardingLocation}', 'ProviderBustripOffersController@updateBoardingLocation')->name('backend.providers.companies.offers.bustrip.updateBoardingLocation');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/bate-volta/rotas/{bustripRoute}/embarques/{bustripBoardingLocation}/deletar', 'ProviderBustripOffersController@destroyBoardingLocation')->name('backend.providers.companies.offers.bustrip.destroyBoardingLocation');

        // Offers - Shuttle
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/nova', 'ProviderShuttleOffersController@createRoute')->name('backend.providers.companies.offers.shuttle.createRoute');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/nova', 'ProviderShuttleOffersController@storeRoute')->name('backend.providers.companies.offers.shuttle.storeRoute');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}', 'ProviderShuttleOffersController@editRoute')->name('backend.providers.companies.offers.shuttle.editRoute');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}', 'ProviderShuttleOffersController@updateRoute')->name('backend.providers.companies.offers.shuttle.updateRoute');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/deletar', 'ProviderShuttleOffersController@destroyRoute')->name('backend.providers.companies.offers.shuttle.destroyRoute');

        // Offers - Shuttle - Boarding Locations
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/embarques/novo', 'ProviderShuttleOffersController@createBoardingLocation')->name('backend.providers.companies.offers.shuttle.createBoardingLocation');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/embarques/novo', 'ProviderShuttleOffersController@storeBoardingLocation')->name('backend.providers.companies.offers.shuttle.storeBoardingLocation');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/embarques/{shuttleBoardingLocation}', 'ProviderShuttleOffersController@editBoardingLocation')->name('backend.providers.companies.offers.shuttle.editBoardingLocation');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/embarques/{shuttleBoardingLocation}', 'ProviderShuttleOffersController@updateBoardingLocation')->name('backend.providers.companies.offers.shuttle.updateBoardingLocation');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/shuttle/rotas/{shuttleRoute}/embarques/{shuttleBoardingLocation}/deletar', 'ProviderShuttleOffersController@destroyBoardingLocation')->name('backend.providers.companies.offers.shuttle.destroyBoardingLocation');

        // Offers - Longtrip
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/nova', 'ProviderLongtripOffersController@createRoute')->name('backend.providers.companies.offers.longtrip.createRoute');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/nova', 'ProviderLongtripOffersController@storeRoute')->name('backend.providers.companies.offers.longtrip.storeRoute');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}', 'ProviderLongtripOffersController@editRoute')->name('backend.providers.companies.offers.longtrip.editRoute');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}', 'ProviderLongtripOffersController@updateRoute')->name('backend.providers.companies.offers.longtrip.updateRoute');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/deletar', 'ProviderLongtripOffersController@destroyRoute')->name('backend.providers.companies.offers.longtrip.destroyRoute');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/longtrip/accommodation/{longtripRoute}', 'ProviderLongtripOffersController@storeLongtripAccommodation')->name('backend.providers.companies.offers.longtrip.accommodation-type.storeLongtripAccommodation');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/longtrip/accommodation/{longtripRoute}/{longtripAccommodation}', 'ProviderLongtripOffersController@destroyLongtripAccommodation')->name('backend.providers.companies.offers.longtrip.accommodation-type.destroyLongtripAccommodation');

        // Offers - Longtrip - Boarding Locations
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/embarques/novo', 'ProviderLongtripOffersController@createBoardingLocation')->name('backend.providers.companies.offers.longtrip.createBoardingLocation');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/embarques/novo', 'ProviderLongtripOffersController@storeBoardingLocation')->name('backend.providers.companies.offers.longtrip.storeBoardingLocation');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/embarques/{longtripBoardingLocation}', 'ProviderLongtripOffersController@editBoardingLocation')->name('backend.providers.companies.offers.longtrip.editBoardingLocation');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/embarques/{longtripBoardingLocation}', 'ProviderLongtripOffersController@updateBoardingLocation')->name('backend.providers.companies.offers.longtrip.updateBoardingLocation');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/embarques/{longtripBoardingLocation}/deletar', 'ProviderLongtripOffersController@destroyBoardingLocation')->name('backend.providers.companies.offers.longtrip.destroyBoardingLocation');

        // Offers - Longtrip - Accommodations
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/acomodacoes/nova/{longtripAccommodation}', 'ProviderLongtripOffersController@createLongtripAccommodationHotel')->name('backend.providers.companies.offers.longtrip.createLongtripAccommodationHotel');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/acomodacoes/nova', 'ProviderLongtripOffersController@storeLongtripAccommodationHotel')->name('backend.providers.companies.offers.longtrip.storeLongtripAccommodationHotel');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/acomodacoes/{longtripAccommodation}/{longtripAccommodationHotel}', 'ProviderLongtripOffersController@editLongtripAccommodationHotel')->name('backend.providers.companies.offers.longtrip.editLongtripAccommodationHotel');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/acomodacoes/{longtripAccommodation}/{longtripAccommodationHotel}', 'ProviderLongtripOffersController@updateLongtripAccommodationHotel')->name('backend.providers.companies.offers.longtrip.updateLongtripAccommodationHotel');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/longtrip/rotas/{longtripRoute}/acomodacoes/{longtripAccommodation}/deletar/{longtripAccommodationHotel}', 'ProviderLongtripOffersController@destroyLongtripAccommodationHotel')->name('backend.providers.companies.offers.longtrip.destroyLongtripAccommodationHotel');


        // Offers - Hotel - Accommodations
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/nova', 'ProviderHotelOffersController@createHotelAccommodation')->name('backend.providers.companies.offers.hotel.createHotelAccommodation');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/nova', 'ProviderHotelOffersController@storeHotelAccommodation')->name('backend.providers.companies.offers.hotel.storeHotelAccommodation');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/{hotelAccommodation}', 'ProviderHotelOffersController@editHotelAccommodation')->name('backend.providers.companies.offers.hotel.editHotelAccommodation');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/{hotelAccommodation}', 'ProviderHotelOffersController@updateHotelAccommodation')->name('backend.providers.companies.offers.hotel.updateHotelAccommodation');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/{hotelAccommodation}/deletar', 'ProviderHotelOffersController@destroyHotelAccommodation')->name('backend.providers.companies.offers.hotel.destroyHotelAccommodation');

        // Offers - Hotel - Accommodations - Gallery
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/hotel/acomodacoes/{hotelAccommodation}/imagens/{image}', 'ProviderHotelOffersController@destroyGalleryImage')->name('backend.providers.companies.offers.hotel.destroyGalleryImage');

        // Offers - Airfare
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/items/novo', 'ProviderAirfareOffersController@createItem')->name('backend.providers.companies.offers.airfare.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/items/novo', 'ProviderAirfareOffersController@storeItem')->name('backend.providers.companies.offers.airfare.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/items/{additional}', 'ProviderAirfareOffersController@editItem')->name('backend.providers.companies.offers.airfare.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/items/{additional}', 'ProviderAirfareOffersController@updateItem')->name('backend.providers.companies.offers.airfare.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/items/{additional}/deletar', 'ProviderAirfareOffersController@destroyItem')->name('backend.providers.companies.offers.airfare.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/grupos/novo', 'ProviderAirfareOffersController@createGroup')->name('backend.providers.companies.offers.airfare.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/grupos/novo', 'ProviderAirfareOffersController@storeGroup')->name('backend.providers.companies.offers.airfare.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/grupos/{additionalGroup}', 'ProviderAirfareOffersController@editGroup')->name('backend.providers.companies.offers.airfare.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/grupos/{additionalGroup}', 'ProviderAirfareOffersController@updateGroup')->name('backend.providers.companies.offers.airfare.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/passagem-aerea/grupos/{additionalGroup}/deletar', 'ProviderAirfareOffersController@destroyGroup')->name('backend.providers.companies.offers.airfare.destroyGroup');

        // Offers - TravelInsurance
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/items/novo', 'ProviderTravelInsuranceOffersController@createItem')->name('backend.providers.companies.offers.travel-insurance.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/items/novo', 'ProviderTravelInsuranceOffersController@storeItem')->name('backend.providers.companies.offers.travel-insurance.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/items/{additional}', 'ProviderTravelInsuranceOffersController@editItem')->name('backend.providers.companies.offers.travel-insurance.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/items/{additional}', 'ProviderTravelInsuranceOffersController@updateItem')->name('backend.providers.companies.offers.travel-insurance.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/items/{additional}/deletar', 'ProviderTravelInsuranceOffersController@destroyItem')->name('backend.providers.companies.offers.travel-insurance.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/grupos/novo', 'ProviderTravelInsuranceOffersController@createGroup')->name('backend.providers.companies.offers.travel-insurance.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/grupos/novo', 'ProviderTravelInsuranceOffersController@storeGroup')->name('backend.providers.companies.offers.travel-insurance.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/grupos/{additionalGroup}', 'ProviderTravelInsuranceOffersController@editGroup')->name('backend.providers.companies.offers.travel-insurance.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/grupos/{additionalGroup}', 'ProviderTravelInsuranceOffersController@updateGroup')->name('backend.providers.companies.offers.travel-insurance.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/seguro-viagem/grupos/{additionalGroup}/deletar', 'ProviderTravelInsuranceOffersController@destroyGroup')->name('backend.providers.companies.offers.travel-insurance.destroyGroup');

        // Offers - Ticket
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/ingresso/items/novo', 'ProviderTicketOffersController@createItem')->name('backend.providers.companies.offers.ticket.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/ingresso/items/novo', 'ProviderTicketOffersController@storeItem')->name('backend.providers.companies.offers.ticket.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/ingresso/items/{additional}', 'ProviderTicketOffersController@editItem')->name('backend.providers.companies.offers.ticket.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/ingresso/items/{additional}', 'ProviderTicketOffersController@updateItem')->name('backend.providers.companies.offers.ticket.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/ingresso/items/{additional}/deletar', 'ProviderTicketOffersController@destroyItem')->name('backend.providers.companies.offers.ticket.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/ingresso/grupos/novo', 'ProviderTicketOffersController@createGroup')->name('backend.providers.companies.offers.ticket.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/ingresso/grupos/novo', 'ProviderTicketOffersController@storeGroup')->name('backend.providers.companies.offers.ticket.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/ingresso/grupos/{additionalGroup}', 'ProviderTicketOffersController@editGroup')->name('backend.providers.companies.offers.ticket.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/ingresso/grupos/{additionalGroup}', 'ProviderTicketOffersController@updateGroup')->name('backend.providers.companies.offers.ticket.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/ingresso/grupos/{additionalGroup}/deletar', 'ProviderTicketOffersController@destroyGroup')->name('backend.providers.companies.offers.ticket.destroyGroup');

        // Offers - Food
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/items/novo', 'ProviderFoodOffersController@createItem')->name('backend.providers.companies.offers.food.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/items/novo', 'ProviderFoodOffersController@storeItem')->name('backend.providers.companies.offers.food.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/items/{additional}', 'ProviderFoodOffersController@editItem')->name('backend.providers.companies.offers.food.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/items/{additional}', 'ProviderFoodOffersController@updateItem')->name('backend.providers.companies.offers.food.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/items/{additional}/deletar', 'ProviderFoodOffersController@destroyItem')->name('backend.providers.companies.offers.food.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/grupos/novo', 'ProviderFoodOffersController@createGroup')->name('backend.providers.companies.offers.food.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/grupos/novo', 'ProviderFoodOffersController@storeGroup')->name('backend.providers.companies.offers.food.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/grupos/{additionalGroup}', 'ProviderFoodOffersController@editGroup')->name('backend.providers.companies.offers.food.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/grupos/{additionalGroup}', 'ProviderFoodOffersController@updateGroup')->name('backend.providers.companies.offers.food.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/alimentacao/grupos/{additionalGroup}/deletar', 'ProviderFoodOffersController@destroyGroup')->name('backend.providers.companies.offers.food.destroyGroup');

        // Offers - Transfer
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/traslado/items/novo', 'ProviderTransferOffersController@createItem')->name('backend.providers.companies.offers.transfer.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/traslado/items/novo', 'ProviderTransferOffersController@storeItem')->name('backend.providers.companies.offers.transfer.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/traslado/items/{additional}', 'ProviderTransferOffersController@editItem')->name('backend.providers.companies.offers.transfer.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/traslado/items/{additional}', 'ProviderTransferOffersController@updateItem')->name('backend.providers.companies.offers.transfer.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/traslado/items/{additional}/deletar', 'ProviderTransferOffersController@destroyItem')->name('backend.providers.companies.offers.transfer.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/traslado/grupos/novo', 'ProviderTransferOffersController@createGroup')->name('backend.providers.companies.offers.transfer.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/traslado/grupos/novo', 'ProviderTransferOffersController@storeGroup')->name('backend.providers.companies.offers.transfer.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/traslado/grupos/{additionalGroup}', 'ProviderTransferOffersController@editGroup')->name('backend.providers.companies.offers.transfer.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/traslado/grupos/{additionalGroup}', 'ProviderTransferOffersController@updateGroup')->name('backend.providers.companies.offers.transfer.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/traslado/grupos/{additionalGroup}/deletar', 'ProviderTransferOffersController@destroyGroup')->name('backend.providers.companies.offers.transfer.destroyGroup');

        // Offers - Additional
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/adicional/items/novo', 'ProviderAdditionalOffersController@createItem')->name('backend.providers.companies.offers.additional.createItem');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/adicional/items/novo', 'ProviderAdditionalOffersController@storeItem')->name('backend.providers.companies.offers.additional.storeItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/adicional/items/{additional}', 'ProviderAdditionalOffersController@editItem')->name('backend.providers.companies.offers.additional.editItem');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/adicional/items/{additional}', 'ProviderAdditionalOffersController@updateItem')->name('backend.providers.companies.offers.additional.updateItem');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/adicional/items/{additional}/deletar', 'ProviderAdditionalOffersController@destroyItem')->name('backend.providers.companies.offers.additional.destroyItem');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/adicional/grupos/novo', 'ProviderAdditionalOffersController@createGroup')->name('backend.providers.companies.offers.additional.createGroup');
        Route::post('{provider}/empresas/{company}/ofertas/{offer}/adicional/grupos/novo', 'ProviderAdditionalOffersController@storeGroup')->name('backend.providers.companies.offers.additional.storeGroup');
        Route::get('{provider}/empresas/{company}/ofertas/{offer}/adicional/grupos/{additionalGroup}', 'ProviderAdditionalOffersController@editGroup')->name('backend.providers.companies.offers.additional.editGroup');
        Route::put('{provider}/empresas/{company}/ofertas/{offer}/adicional/grupos/{additionalGroup}', 'ProviderAdditionalOffersController@updateGroup')->name('backend.providers.companies.offers.additional.updateGroup');
        Route::delete('{provider}/empresas/{company}/ofertas/{offer}/adicional/grupos/{additionalGroup}/deletar', 'ProviderAdditionalOffersController@destroyGroup')->name('backend.providers.companies.offers.additional.destroyGroup');
    });

    //Reports
    Route::group(['prefix' => 'reports'], function () {
        Route::get('report_bookings'    , 'ReportsController@report_bookings')->name('backend.reports.reports.bookings.index');
        Route::get('report_newsletter'  , 'ReportsController@report_newsletter')->name('backend.reports.report_newsletter.index');
        Route::get('report_event'       , 'ReportsController@report_event')->name('backend.reports.report_event.index');
        Route::get('report_stock'       , 'ReportsController@report_stock')->name('backend.reports.report_stock.index');
        Route::get('report_detail_booking', 'ReportsController@report_detail_booking')->name('backend.reports.report_detail_booking.index');
        Route::get('report_email'       , 'ReportsController@report_email')->name('backend.reports.report_email.index');
        Route::get('report_bills'       , 'ReportsController@report_bills')->name('backend.reports.report_bills.index');
        Route::get('report_refund'      , 'ReportsController@report_refund')->name('backend.reports.report_refund.index');
        Route::get('report_payments'    , 'ReportsController@report_payments')->name('backend.reports.report_payments.index');
        Route::get('report_accountant'  , 'ReportsController@report_accountant')->name('backend.reports.report_accountant.index');
        Route::get('report_payment_providers', 'ReportsController@report_payment_providers')->name('backend.reports.report_payment_providers.index');
    });

    // Offers
    Route::group(['prefix' => 'ofertas'], function () {
        Route::get('/', 'OffersController@index')->name('backend.offers.index');
        Route::get('/nova', 'OffersController@create')->name('backend.offers.create');
        Route::get('{offer}/replicar', 'ProviderOffersController@replicate')->name('backend.offers.replicate');
        Route::post('{offer}/replicar', 'ProviderOffersController@storeReplicate')->name('backend.offers.storeReplicate');
        Route::get('{offer}/replicate_extra/{package}', 'ProviderOffersController@replicate_extra')->name('backend.providers.companies.offers.replicate_extra');
    });

    // Packages
    Route::group(['prefix' => 'pacotes'], function () {
        Route::get('/', 'PackageController@index')->name('backend.packages.index');
        Route::get('/novo', 'PackageController@create')->name('backend.packages.create');
        Route::post('/novo', 'PackageController@store')->name('backend.packages.store');
        Route::get('/{package}', 'PackageController@edit')->name('backend.packages.edit');
        Route::put('/{package}', 'PackageController@update')->name('backend.packages.update');
        Route::get('/{package}/ofertas', 'PackageController@offers')->name('backend.packages.offers');
        Route::delete('/{package}/deletar', 'PackageController@destroy')->name('backend.packages.destroy');
        Route::delete('/{package}/formas-de-pagamento/{id}/deletar', 'PackageController@destroyPaymentMethod')->name('backend.packages.destroyPaymentMethod');
        Route::get('/{package}/formas-de-pagamento/nova', 'PackageController@createPaymentMethod')->name('backend.packages.createPaymentMethod');
        Route::post('/{package}/formas-de-pagamento/nova', 'PackageController@storePaymentMethod')->name('backend.packages.storePaymentMethod');
    });

    // Prebookings
    Route::group(['prefix' => 'prereservas'], function () {
        Route::get('/', 'PrebookingController@index')->name('backend.prebookings.index');
        Route::get('/novo', 'PrebookingController@create')->name('backend.prebookings.create');
        Route::post('/novo', 'PrebookingController@store')->name('backend.prebookings.store');
        Route::post('/filtrar', 'PrebookingController@filter')->name('backend.prebookings.filter');
        Route::get('/filtrar', 'PrebookingController@index');
        Route::get('/{prebooking}', 'PrebookingController@edit')->name('backend.prebookings.edit');
        Route::put('/{prebooking}', 'PrebookingController@update')->name('backend.prebookings.update');
        Route::delete('/{prebooking}/deletar', 'PrebookingController@destroy')->name('backend.prebookings.destroy');
    });

    // Bookings
    Route::group(['prefix' => 'reservas'], function () {
        Route::get('/', 'BookingController@index')->name('backend.bookings.index');
        Route::get('/novo', 'BookingController@create')->name('backend.bookings.create');
        Route::post('/novo', 'BookingController@store')->name('backend.bookings.store');
        Route::get('/filtrar', 'BookingController@filter')->name('backend.bookings.filter');
        Route::get('/{booking}', 'BookingController@edit')->name('backend.bookings.edit');
        Route::put('/{booking}', 'BookingController@update')->name('backend.bookings.update');
        Route::post('/{booking}/historico', 'BookingController@storeLog')->name('backend.bookings.storeLog');
        Route::post('/{booking}/historico/deletar', 'BookingController@destroyLog')->name('backend.bookings.deleteLog');
        Route::delete('/{booking}/deletar', 'BookingController@destroy')->name('backend.bookings.destroy');
        Route::get('/{booking}/cancelar', 'BookingController@cancel')->name('backend.bookings.cancel');
        Route::get('/{booking}/contrato', 'BookingController@contract')->name('backend.bookings.contract');
        Route::get('/{booking}/confirmacao', 'BookingController@confirmation')->name('backend.bookings.confirmation');

        // Booking Passengers
        Route::group(['prefix' => '/{booking}/passegeiros'], function () {
            Route::get('/novo', 'BookingController@createPassenger')->name('backend.bookings.createPassenger');
            Route::post('/novo', 'BookingController@storePassenger')->name('backend.bookings.storePassenger');
            Route::delete('/{bookingPassenger}/deletar', 'BookingController@destroyPassenger')->name('backend.bookings.destroyPassenger');
        });

        // Booking Passenger Additionals
        Route::group(['prefix' => '/{booking}/adicionais'], function () {
            Route::get('/novo', 'BookingController@createAdditional')->name('backend.bookings.createAdditional');
            Route::post('/novo', 'BookingController@storeAdditional')->name('backend.bookings.storeAdditional');
            Route::delete('/{bookingPassengerAdditional}/deletar', 'BookingController@destroyAdditional')->name('backend.bookings.destroyAdditional');
        });

        // Booking Products
        Route::group(['prefix' => '/{booking}/product'], function () {
            Route::get('/novo', 'BookingController@createProduct')->name('backend.bookings.createProduct');
            Route::post('/novo', 'BookingController@storeProduct')->name('backend.bookings.storeProduct');
            Route::delete('/{bookingProduct}/deletar', 'BookingController@destroyProduct')->name('backend.bookings.destroyProduct');
        });

        // Booking Vouchers
        Route::group(['prefix' => '/{booking}/vouchers'], function () {
            Route::get('/novo', 'BookingController@createVoucher')->name('backend.bookings.createVoucher');
            Route::post('/novo', 'BookingController@storeVoucher')->name('backend.bookings.storeVoucher');
            Route::delete('/arquivo/{bookingVoucherFile}/deletar', 'BookingController@destroyVoucherFile')->name('backend.bookings.destroyVoucherFile');
            Route::delete('/{bookingVoucher}/deletar', 'BookingController@destroyVoucher')->name('backend.bookings.destroyVoucher');
            Route::get('/view/{bookingVoucher}', 'BookingController@viewVoucher')->name('backend.bookings.viewVoucher');

        });

        // Booking Bills
        Route::group(['prefix' => '/{booking}/faturas'], function () {
            Route::get('/novo', 'BookingController@createBill')->name('backend.bookings.createBill');
            Route::post('/novo', 'BookingController@storeBill')->name('backend.bookings.storeBill');
            Route::get('/gerar', 'BookingController@generateBill')->name('backend.bookings.generateBill');
            Route::delete('/{bookingBill}/deletar', 'BookingController@destroyBill')->name('backend.bookings.destroyBill');
            Route::get('/{bookingBill}/cancelar', 'BookingController@cancelBill')->name('backend.bookings.cancelBill');
            Route::get('/{bookingBill}/pagar', 'BookingController@payBill')->name('backend.bookings.payBill');
            Route::get('/{bookingBill}/restaurar', 'BookingController@restoreBill')->name('backend.bookings.restoreBill');
        });

        // Booking Notification
        Route::group(['prefix' => '/{booking}/notification'], function () {
            Route::get('/', 'BookingController@sendBookingNotification')->name('backend.bookings.bookingNotification');
        });

    });

    // Inclusions
    Route::group(['prefix' => 'inclusoes'], function () {

        // Inclusion Groups
        Route::get('/{type}/grupos', 'InclusionGroupController@index')->name('backend.inclusions.groups.index');
        Route::get('/{type}/grupos/novo', 'InclusionGroupController@create')->name('backend.inclusions.groups.create');
        Route::post('/{type}/grupos/novo', 'InclusionGroupController@store')->name('backend.inclusions.groups.store');
        Route::get('/{type}/grupos/{inclusionGroup}', 'InclusionGroupController@edit')->name('backend.inclusions.groups.edit');
        Route::put('/{type}/grupos/{inclusionGroup}', 'InclusionGroupController@update')->name('backend.inclusions.groups.update');
        Route::delete('/{type}/grupos/{inclusionGroup}/deletar', 'InclusionGroupController@destroy')->name('backend.inclusions.groups.destroy');

        Route::get('/{type}', 'InclusionController@index')->name('backend.inclusions.index');
        Route::get('/{type}/nova', 'InclusionController@create')->name('backend.inclusions.create');
        Route::post('/{type}/nova', 'InclusionController@store')->name('backend.inclusions.store');
        Route::get('/{type}/{inclusion}', 'InclusionController@edit')->name('backend.inclusions.edit');
        Route::put('/{type}/{inclusion}', 'InclusionController@update')->name('backend.inclusions.update');
        Route::delete('/{type}/{inclusion}/deletar', 'InclusionController@destroy')->name('backend.inclusions.destroy');
    });

    // Exclusions
    Route::group(['prefix' => 'exclusoes'], function () {

        // Exclusion Groups
        Route::get('/{type}/grupos', 'ExclusionGroupController@index')->name('backend.exclusions.groups.index');
        Route::get('/{type}/grupos/novo', 'ExclusionGroupController@create')->name('backend.exclusions.groups.create');
        Route::post('/{type}/grupos/novo', 'ExclusionGroupController@store')->name('backend.exclusions.groups.store');
        Route::get('/{type}/grupos/{exclusionGroup}', 'ExclusionGroupController@edit')->name('backend.exclusions.groups.edit');
        Route::put('/{type}/grupos/{exclusionGroup}', 'ExclusionGroupController@update')->name('backend.exclusions.groups.update');
        Route::delete('/{type}/grupos/{exclusionGroup}/deletar', 'ExclusionGroupController@destroy')->name('backend.exclusions.groups.destroy');

        Route::get('/{type}', 'ExclusionController@index')->name('backend.exclusions.index');
        Route::get('/{type}/nova', 'ExclusionController@create')->name('backend.exclusions.create');
        Route::post('/{type}/nova', 'ExclusionController@store')->name('backend.exclusions.store');
        Route::get('/{type}/{exclusion}', 'ExclusionController@edit')->name('backend.exclusions.edit');
        Route::put('/{type}/{exclusion}', 'ExclusionController@update')->name('backend.exclusions.update');
        Route::delete('/{type}/{exclusion}/deletar', 'ExclusionController@destroy')->name('backend.exclusions.destroy');
    });

    // Observations
    Route::group(['prefix' => 'observacoes'], function () {
        Route::get('/{type}', 'ObservationController@index')->name('backend.observations.index');
        Route::get('/{type}/nova', 'ObservationController@create')->name('backend.observations.create');
        Route::post('/{type}/nova', 'ObservationController@store')->name('backend.observations.store');
        Route::get('/{type}/{observation}', 'ObservationController@edit')->name('backend.observations.edit');
        Route::put('/{type}/{observation}', 'ObservationController@update')->name('backend.observations.update');
        Route::delete('/{type}/{observation}/deletar', 'ObservationController@destroy')->name('backend.observations.destroy');
    });

    // Additionals
    Route::group(['prefix' => 'adicionais'], function () {
        // Additional Groups
        // Route::get('/grupos', 'AdditionalGroupController@index')->name('backend.additionals.groups.index');
        // Route::get('/grupos/novo', 'AdditionalGroupController@create')->name('backend.additionals.groups.create');
        // Route::post('/grupos/novo', 'AdditionalGroupController@store')->name('backend.additionals.groups.store');
        // Route::get('/grupos/{additionalGroup}', 'AdditionalGroupController@edit')->name('backend.additionals.groups.edit');
        // Route::put('/grupos/{additionalGroup}', 'AdditionalGroupController@update')->name('backend.additionals.groups.update');
        // Route::get('/grupos/{additionalGroup}/deletar', 'AdditionalGroupController@destroy')->name('backend.additionals.groups.destroy');

        // Route::get('/', 'AdditionalController@index')->name('backend.additionals.index');
        // Route::get('/novo', 'AdditionalController@create')->name('backend.additionals.create');
        // Route::post('/novo', 'AdditionalController@store')->name('backend.additionals.store');
        // Route::get('/{additional}', 'AdditionalController@edit')->name('backend.additionals.edit');
        // Route::put('/{additional}', 'AdditionalController@update')->name('backend.additionals.update');
        // Route::delete('/{additional}/deletar', 'AdditionalController@destroy')->name('backend.additionals.destroy');
    });

    // Sale Coefficients
    Route::group(['prefix' => 'coeficients-de-venda'], function () {
        Route::get('/', 'SaleCoefficientController@index')->name('backend.saleCoefficients.index');
        Route::get('/novo', 'SaleCoefficientController@create')->name('backend.saleCoefficients.create');
        Route::post('/novo', 'SaleCoefficientController@store')->name('backend.saleCoefficients.store');
        Route::get('/{saleCoefficient}', 'SaleCoefficientController@edit')->name('backend.saleCoefficients.edit');
        Route::put('/{saleCoefficient}', 'SaleCoefficientController@update')->name('backend.saleCoefficients.update');
        Route::delete('/{saleCoefficient}/deletar', 'SaleCoefficientController@destroy')->name('backend.saleCoefficients.destroy');
    });

    // Images
    Route::group(['prefix' => 'imagens'], function () {
        Route::get('/', 'ImageController@index')->name('backend.images.index');
        Route::get('/filtrar', 'ImageController@filter')->name('backend.images.filter');
        Route::get('/nova', 'ImageController@create')->name('backend.images.create');
        Route::post('/nova', 'ImageController@store')->name('backend.images.store');
        Route::get('/{image}', 'ImageController@edit')->name('backend.images.edit');
        Route::put('/{image}', 'ImageController@update')->name('backend.images.update');
        Route::delete('/{image}/deletar', 'ImageController@destroy')->name('backend.images.destroy');
    });

    // Invoices
    Route::group(['prefix' => '', 'as' => 'backend.'], function () {
        Route::resource('invoice-information', 'InvoiceInformationController')->names([
            'edit' => 'invoiceInformation.edit',
            'destroy' => 'invoiceInformation.destroy',
            'store' => 'invoiceInformation.store',
            'index' => 'invoiceInformation.index',
            'create' => 'invoiceInformation.create',
            'update' => 'invoiceInformation.update',
            'show' => 'invoiceInformation.show'
        ]);
    });

    // Payment Methods
    Route::group(['prefix' => 'formas-de-pagamento'], function () {
        Route::get('/', 'PaymentMethodController@index')->name('backend.paymentMethods.index');
        Route::get('/template/create'   , 'PaymentMethodController@createTemplate')->name('backend.paymentMethods.createTemplate');
        Route::post('/template/create'  , 'PaymentMethodController@storeTemplate')->name('backend.paymentMethods.storeTemplate');
        Route::put('/template'          , 'PaymentMethodController@updateTemplate')->name('backend.paymentMethods.updateTemplate');
        Route::delete('/template/{paymentMethodTemplate}/deletar', 'PaymentMethodController@destroyTemplate')->name('backend.paymentMethods.destroy');
        Route::get('/{paymentMethod}', 'PaymentMethodController@edit')->name('backend.paymentMethods.edit');
        Route::put('/{paymentMethod}', 'PaymentMethodController@update')->name('backend.paymentMethods.update');
    });

    // Promocodes
    Route::group(['prefix' => 'promocodes'], function () {

        // Promocode Groups
        Route::get('/', 'PromocodeController@index')->name('backend.promocodes.index');
        Route::get('/grupos/novo', 'PromocodeController@createGroup')->name('backend.promocodes.groups.create');
        Route::post('/grupos/novo', 'PromocodeController@storeGroup')->name('backend.promocodes.groups.store');
        Route::get('/grupos/{promocodeGroup}', 'PromocodeController@editGroup')->name('backend.promocodes.groups.edit');
        Route::put('/grupos/{promocodeGroup}', 'PromocodeController@updateGroup')->name('backend.promocodes.groups.update');
        Route::delete('/grupos/{promocodeGroup}/deletar', 'PromocodeController@destroyGroup')->name('backend.promocodes.groups.destroy');

        // Promocodes
        Route::get('/grupos/{promocodeGroup}/promocodes/novo', 'PromocodeController@create')->name('backend.promocodes.create');
        Route::post('/grupos/{promocodeGroup}/promocodes/novo', 'PromocodeController@store')->name('backend.promocodes.store');
        Route::get('/grupos/{promocodeGroup}/promocodes/{promocode}', 'PromocodeController@edit')->name('backend.promocodes.edit');
        Route::put('/grupos/{promocodeGroup}/promocodes/{promocode}', 'PromocodeController@update')->name('backend.promocodes.update');
        Route::delete('/grupos/{promocodeGroup}/promocodes/{promocode}/deletar', 'PromocodeController@destroy')->name('backend.promocodes.destroy');
    });

    // Currencies
    Route::group(['prefix' => 'moedas'], function () {
        Route::get('/', 'CurrencyController@index')->name('backend.currencies.index');
        Route::get('/{currencyQuotation}', 'CurrencyController@edit')->name('backend.currencies.edit');
        Route::put('/', 'CurrencyController@update')->name('backend.currencies.update');
    });

    // Configs
    Route::group(['prefix' => 'configuracoes'], function () {
        Route::group(['prefix' => 'providers/hotel/tipos-de-acomodacao'], function () {
            Route::get('/', 'HotelAccommodationTypeController@index')->name('backend.configs.providers.hotel.accommodation-types.index');
            Route::get('/novo', 'HotelAccommodationTypeController@create')->name('backend.configs.providers.hotel.accommodation-types.create');
            Route::post('/novo', 'HotelAccommodationTypeController@store')->name('backend.configs.providers.hotel.accommodation-types.store');
            Route::get('/{hotelAccommodationType}', 'HotelAccommodationTypeController@edit')->name('backend.configs.providers.hotel.accommodation-types.edit');
            Route::put('/{hotelAccommodationType}', 'HotelAccommodationTypeController@update')->name('backend.configs.providers.hotel.accommodation-types.update');
            Route::delete('/{hotelAccommodationType}/deletar', 'HotelAccommodationTypeController@destroy')->name('backend.configs.providers.hotel.accommodation-types.destroy');
        });

        Route::group(['prefix' => 'providers/hotel/estrutura-de-acomodacao'], function () {
            Route::get('/', 'HotelAccommodationStructureController@index')->name('backend.configs.providers.hotel.accommodation-structure.index');
            Route::get('/novo', 'HotelAccommodationStructureController@create')->name('backend.configs.providers.hotel.accommodation-structure.create');
            Route::post('/novo', 'HotelAccommodationStructureController@store')->name('backend.configs.providers.hotel.accommodation-structure.store');
            Route::get('/{hotelAccommodationStructure}', 'HotelAccommodationStructureController@edit')->name('backend.configs.providers.hotel.accommodation-structure.edit');
            Route::put('/{hotelAccommodationStructure}', 'HotelAccommodationStructureController@update')->name('backend.configs.providers.hotel.accommodation-structure.update');
            Route::delete('/{hotelAccommodationStructure}/deletar', 'HotelAccommodationStructureController@destroy')->name('backend.configs.providers.hotel.accommodation-structure.destroy');
        });

        Route::group(['prefix' => 'providers/hotel/estrutura-de-hotel'], function () {
            Route::get('/', 'HotelStructureController@index')->name('backend.configs.providers.hotel.hotel-structure.index');
            Route::get('/novo', 'HotelStructureController@create')->name('backend.configs.providers.hotel.hotel-structure.create');
            Route::post('/novo', 'HotelStructureController@store')->name('backend.configs.providers.hotel.hotel-structure.store');
            Route::get('/{hotelStructure}', 'HotelStructureController@edit')->name('backend.configs.providers.hotel.hotel-structure.edit');
            Route::put('/{hotelStructure}', 'HotelStructureController@update')->name('backend.configs.providers.hotel.hotel-structure.update');
            Route::delete('/{hotelStructure}/deletar', 'HotelStructureController@destroy')->name('backend.configs.providers.hotel.hotel-structure.destroy');
        });

        Route::group(['prefix' => 'providers/longtrip/tipos-de-acomodacao'], function () {
            Route::get('/', 'LongtripAccommodationTypeController@index')->name('backend.configs.providers.longtrip.accommodation-types.index');
            Route::get('/novo', 'LongtripAccommodationTypeController@create')->name('backend.configs.providers.longtrip.accommodation-types.create');
            Route::post('/novo', 'LongtripAccommodationTypeController@store')->name('backend.configs.providers.longtrip.accommodation-types.store');
            Route::get('/{longtripAccommodationType}', 'LongtripAccommodationTypeController@edit')->name('backend.configs.providers.longtrip.accommodation-types.edit');
            Route::put('/{longtripAccommodationType}', 'LongtripAccommodationTypeController@update')->name('backend.configs.providers.longtrip.accommodation-types.update');
            Route::delete('/{longtripAccommodationType}/deletar', 'LongtripAccommodationTypeController@destroy')->name('backend.configs.providers.longtrip.accommodation-types.destroy');
        });

        Route::group(['prefix' => 'slideshow'], function () {
            Route::get('/', 'SlideshowController@index')->name('backend.configs.slideshow.index');
            Route::get('/novo', 'SlideshowController@create')->name('backend.configs.slideshow.create');
            Route::post('/novo', 'SlideshowController@store')->name('backend.configs.slideshow.store');
            Route::get('/{image}', 'SlideshowController@edit')->name('backend.configs.slideshow.edit');
            Route::put('/{image}', 'SlideshowController@update')->name('backend.configs.slideshow.update');
            Route::delete('/{image}/deletar', 'SlideshowController@destroy')->name('backend.configs.slideshow.destroy');
        });

        Route::group(['prefix' => 'usuarios'], function () {
            Route::get('/', 'UserController@index')->name('backend.configs.users.index');
            Route::get('/novo', 'UserController@create')->name('backend.configs.users.create');
            Route::post('/novo', 'UserController@store')->name('backend.configs.users.store');
            Route::get('/{user}', 'UserController@edit')->name('backend.configs.users.edit');
            Route::put('/{user}', 'UserController@update')->name('backend.configs.users.update');
            Route::delete('/{user}/deletar', 'UserController@destroy')->name('backend.configs.users.destroy');
        });
    });

    // Static pages
    Route::group(['prefix' => 'paginas-estaticas'], function () {
        Route::group(['prefix' => 'grupos'], function () {
            Route::get('/', 'PageGroupController@index')->name('backend.pages.groups.index');
            Route::get('/novo', 'PageGroupController@create')->name('backend.pages.groups.create');
            Route::post('/novo', 'PageGroupController@store')->name('backend.pages.groups.store');
            Route::get('/{pageGroup}', 'PageGroupController@edit')->name('backend.pages.groups.edit');
            Route::put('/{pageGroup}', 'PageGroupController@update')->name('backend.pages.groups.update');
            Route::delete('/{pageGroup}/deletar', 'PageGroupController@destroy')->name('backend.pages.groups.destroy');
        });

        Route::get('/', 'PageController@index')->name('backend.pages.index');
        Route::get('/nova', 'PageController@create')->name('backend.pages.create');
        Route::post('/nova', 'PageController@store')->name('backend.pages.store');
        Route::get('/{page}', 'PageController@edit')->name('backend.pages.edit');
        Route::put('/{page}', 'PageController@update')->name('backend.pages.update');
        Route::delete('/{page}/deletar', 'PageController@destroy')->name('backend.pages.destroy');
    });

    // Financial
    Route::group(['prefix' => 'financeiro'], function () {
        Route::get('recebiveis' , 'FinancialController@bills')->name('backend.financial.bills');
        Route::get('decryptor'  , 'FinancialController@decryptor')->name('backend.financial.decryptor');
        Route::post('decrypt'  , 'FinancialController@decrypt')->name('backend.financial.decrypt');
    });

    Route::group(['prefix' => 'relatorios'], function () {
        // Bookings
        Route::group(['prefix' => 'reservas'], function () {
            Route::post('/cancel_bill/{booking_bill}', 'BookingBillController@cancelBill')->name('backend.reports.booking_bills.cancel_bill');
            Route::get('/exportar', 'BookingController@reportExport')->name('backend.reports.bookings.export');
        });

        // Newsletter
        Route::group(['prefix' => 'newsletter'], function () {
            Route::get('/', 'NewsletterController@index')->name('backend.reports.newsletters.index');
            Route::get('/exportar', 'NewsletterController@export')->name('backend.reports.newsletters.export');
            Route::delete('/{newsletter}/deletar', 'NewsletterController@destroy')->name('backend.reports.newsletters.destroy');
        });
    });

    // Logout
    Route::post('logout', 'Auth\LoginController@logout')->name('backend.logout');


});

// Authtentication
Route::middleware(['guest'])->group(function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('backend.login');
    Route::post('login', 'Auth\LoginController@login')->name('backend.authenticate');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('backend.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('backend.password.update');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backend.password.email');
});
