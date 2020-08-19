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
Route::post('/ChangeDataCityBK', 'BookingReportController@ChangeCity');
Route::any('/SearchDataBooking','BookingReportController@SearchBooking');
Route::any('/SearchDataBooking/Exportexcel', 'BookingReportController@exportExcel');

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
Route::get('/Flight/{param}', 'FlightReportController@Flight')->name('Flight');
Route::post('/ChangeDataCityFlight', 'FlightReportController@ChangeCity');
Route::post('/ChangeDataAirlineFlight', 'FlightReportController@ChangeAirline');
Route::post('/ChangeDataToFlight', 'FlightReportController@ChangeTo');
Route::post('/SearchDataFlight', 'FlightReportController@SearchDataFlight');


//Guide Calling Report
Route::get('/GuideCalling', 'GuideCallingReportController@GuideCalling')->name('GuideCalling');
Route::post('/GuideCalling/search', 'GuideCallingReportController@SearchGuideCalling');
Route::post('/GuideCalling/export', 'GuideCallingReportController@ExportGuideCalling');

//Booking and Quotation Assignment
Route::get('/JobTransfer', 'JobTransferController@JobTransfer')->name('JobTransfer');
Route::post('/JobTransfer/search', 'JobTransferController@SearchJobTransfer');
Route::get('/JobTransfer/bookingdetail/{param}', 'JobTransferController@DetailTourJobTransfer');
Route::get('/JobTransfer/quotedetail/{param}', 'JobTransferController@DetailQuoteJobTransfer');
Route::post('/JobTransfer/transfer-b-tc', 'JobTransferController@UpdateTCBooking');
Route::post('/JobTransfer/transfer-b-td', 'JobTransferController@UpdateTDBooking');
Route::post('/JobTransfer/transfer-q-tc', 'JobTransferController@UpdateTCQuotation');
Route::post('/JobTransfer/transfer-invoice', 'JobTransferController@UpdateInvoiceContact');

//OP Booking Overview
Route::get('/Overview/{tourid}/{ssid}', 'BookingOverviewController@getBookingOverview')->name('BookingOverview');


//Supplier Database
// Route::get('/SupplierDB', function () {
//     return view('/Supplier/Supplierreport');
// });
Route::get('/SupplierDB/{param}', 'SupplierController@SupplierIndex');
Route::post('/ChangeDataCitySupplier', 'SupplierController@ChangeDataCitySupplier');
Route::post('/ChangeDataSupplyType', 'SupplierController@ChangeDataSupplyType');
Route::post('/ChangeDataSupplier', 'SupplierController@ChangeDataSupplier');
Route::post('/ChangeDataServiceName', 'SupplierController@ChangeDataServiceName');
Route::post('/SearchSupplier', 'SupplierController@SearchSupplier');
Route::any('/SearchDataSupplier/Exportexcel', 'SupplierController@Exportexcel');

// Specialreport
Route::any('/BookingTimeReport', 'SpecialreportController@BookingTimeReport');
Route::post('/ReportBookingTime', 'SpecialreportController@ReportBookingTime');
Route::post('/ReportBookingTime/Exportexcel', 'SpecialreportController@Exportexcel');
