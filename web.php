<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/Booking', function () {
//     return view('/booking/bookdatareport');
// });

// Booking
Route::get('/Booking/{param}', 'BookingReportController@Booking')->name('Booking');
Route::post('/ChangeDataRoom', 'BookingReportController@ChangeRoom');
Route::post('/ChangeDataHotel', 'BookingReportController@ChangeHotel');
Route::post('/ChangeDataCity', 'BookingReportController@ChangeCity');
Route::any('/SearchDataBooking','BookingReportController@SearchBooking');

//Restaurant
/*Route::get('/Restaurant', function () {
    return view('/restaurant/restaurantreport');
});*/
Route::get('/Restaurant/{param}', 'RestaurantReportController@Restaurant')->name('Restaurant');
Route::post('/ChangeDataCity', 'RestaurantReportController@ChangeCity');
Route::post('/ChangeDataRestaurant', 'RestaurantReportController@ChangeRes');
Route::any('/SearchDataRestaurant', 'RestaurantReportController@SearchRestaurant');

//Flight

// Route::get('/Flight', function () {
//     return view('/flight/flightreport');
// });
Route::get('/Flight', 'FlightReportController@Flight')->name('Flight');
Route::post('/ChangeDataCityFlight', 'FlightReportController@ChangeCity');
Route::post('/ChangeDataAirlineFlight', 'FlightReportController@ChangeAirline');
Route::post('/ChangeDataToFlight', 'FlightReportController@ChangeTo');
Route::post('/SearchDataFlight', 'FlightReportController@SearchDataFlight');


//Guide Calling Report
Route::get('/GuideCalling', 'GuideCallingReportController@GuideCalling')->name('GuideCalling');
