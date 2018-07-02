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

Route::get('/', function () {
    return view('welcome');
    //return view('layouts.app_template');
});

/*Route::get('/', function(){
	$redis = app()->make('redis');
	$redis->set("key1", "testValue");
	return $redis->get("key1");
});
*/
//Auth::routes();


/***
Mail Route  ***/

/** CMO Route **/
    Route::get('approve/cmo/{id}/{wfkeyid}/{webApprv}', 'ApproveController@CMOApprover')->name('cmo.approve');
    Route::get('reject/cmo/{id}/{wfkeyid}/{webApprv}', 'ApproveController@CMORejected')->name('cmo.reject');
/*** **/

Route::group(['prefix' => 'admin','namespace' => 'Auth'],function(){
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.token');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('new.password.reset');
});
 



Route::get('/home', 'HomeController@index')->name('home');

Route::get('/upload_excel', 'ImportExcelController@show_upload')->name('upload_excel');
Route::get('/fleet_back', 'ImportExcelController@fleet_back')->name('fleet_back');

Route::group(['prefix' => 'super_admin'], function () {
    Voyager::routes();
});

//**Group Activity
Route::resource('/group_activity', 'GrpActivityController');
//**Group Activity **//

//users
Route::resource('/user_setting', 'Users');
Route::get('/user_setting/index', 'Users@index')->name('list_users');
Route::POST('/user_setting/save', 'Users@store')->name('store.access');
Route::delete('/user_setting/delete', 'Users@destroy')->name('delete_akses');
Route::get('/user_setting/getEmail/{person_id}', 'Users@getEmail')->name('getEmail');
Route::get('/user_setting/getName/{person_id}', 'Users@getName')->name('getName');
//users

Route::get('importExport','ImportExcelController@show_upload');
Route::get('downloadExcel/{type}', 'ImportExcelController@dowloadExcel');
Route::post('importExcel','ImportExcelController@importExcel');

//upload Orders Route
Route::resource('order', 'OrdersController');
//Get
Route::get('/orders/upload_order', 'OrdersController@upload_orders');
Route::get('/orders/fleet_details/{dlvdate}', 'OrdersController@show_fleets')->name('fleet_details');
Route::get('/orders/show_detail_order/{header_id}', 'OrdersController@show_detail_order')->name('show_detail_order');
//posts
Route::post('/upload_excel', 'OrdersController@save_orders')->name('save_orders');
// ************ // 
//Master//
//**** Fleet //
Route::resource('/master/masterfleet', 'FleetController');
Route::get('/master/masterfleet', 'FleetController@index')->name('list_fleets');
Route::post('/master/masterfleet', 'FleetController@store');
//**** customer Fleet//
Route::resource('/master/customer_fleet', 'CustomerFleet');
Route::get('/master/customer_fleet/list', 'CustomerFleet@index')->name('list_cust');
Route::get('/master/customer_fleet/getData','CustomerFleet@CustShipData' )->name('CustShipData');
Route::post('/master/customer_fleet/search', 'CustomerFleet@search')->name('customerF.search');
Route::post('/master/customer_fleet/save', 'CustomerFleet@store')->name('custFleet_store');
//Route::post('/master/customer_fleet/delete', 'CustomerFleet@destroy')->name('custFleet_destroy');
Route::delete('/master/customer_fleet/{files}', 'CustomerFleet@destroy')->name('delete_route');
//**** Region //
Route::resource('/master/region', 'MtlRegionController');
//Route::get('/master/region', 'MtlRegionController@index')->name('list_region');
//**** SubRegion //
Route::resource('/master/subregion', 'MtlSubRegionController');
//Route::get('/master/subregion', 'SubRegionController@index')->name('list_subregion');
//**** Area //
Route::resource('/master/area', 'MtlAreaController');
//Route::get('/master/area', 'MtlAreaController@index')->name('area.index');
//Master//
//**** customer attribute//
Route::resource('/master/customer_attr', 'CustomerAttr');
Route::post('/master/customer_attr', 'CustomerAttr@store')->name('custAttr_store'); 
Route::get('/master/customer_attr/list', 'CustomerAttr@index')->name('list_cust_attr');
Route::post('/master/customer_attr/search', 'CustomerAttr@search')->name('cust_attrF.search');
Route::get('/master/customer_attr/create/{id}', 'CustomerAttr@create')->name('create_attr');
Route::post('/master/customer_attr/storePIC', 'CustomerAttr@storePIC')->name('custAttr.storePIC');
Route::PUT('/master/custAttr_upd/{id}','CustomerAttr@update')->name('update.Attr');
Route::delete('/master/customer_attr/delPic/{id}/{cust_ship_id}', 'CustomerAttr@DeletePIC')->name('delete_pic');
Route::get('/master/customer_attr/GetPArea/{areaID}', 'CustomerAttr@getPersonArea');
Route::get('/master/customer_attr/getMOQ/{fleetID}', 'CustomerAttr@getFleetMOQ');

//**** Persons//
//Route::resource('/persons', 'PersonController');
Route::POST('/persons','PersonController@store')->name('persons.store');
Route::get('/persons','PersonController@index')->name('persons.index');
Route::get('/persons/create','PersonController@create')->name('persons.create');
Route::get('/persons/{person}/edit','PersonController@edit')->name('persons.edit');
Route::delete('/persons/{person}','PersonController@destroy')->name('persons.destroy');
Route::get('/persons/{person}','PersonController@show')->name('persons.show');
Route::PATCH('/persons/update/{person}','PersonController@update')->name('update_P');
Route::PUT('/persons/update/{person}','PersonController@update')->name('update_P');
Route::POST('/persons/update/{person}','PersonController@update')->name('update_P');
//***** Person
Route::get('/persons/list', 'PersonController@index')->name('list_person');
Route::post('/persons/search', 'PersonController@search')->name('person.search');
Route::post('persons/{person}', 'PersonController@update')->name('persons.update');
//dropdown dependent 
Route::get('/persons/getSubregion/{region}', 'PersonController@getsubregion');
Route::get('/persons/getArea/{subregion}', 'PersonController@getarea');
//**** End Persons //
//**** Reports*****//
Route::get('/reports/order_summary', 'ReportsController@FindCMO')->name('search_CMO');
//**** End Reports
//**** Hirarki
Route::resource('wf_hirarki','HirarkiController');
Route::get('/master/wf_hirarki/getHolder/{posID}','HirarkiController@getHolder'); 
Route::post('/master/wf_hirarki/save', 'HirarkiController@simpan')->name('wf_hirarki.structure'); 
//****
//**** Master Positions
Route::resource('/master/positions', 'PositionsController');
//**** End Master Positions 
//**** Master PositionsMtlJobsController');
//**** End Master Positions 
//**** Master Kategory Activity
Route::resource('/master/kategory_activity', 'GrpActivityController');
//****
//**** Master  Activity
Route::resource('/master/activity', 'MtlActivityController');
//***
//*** Budget
Route::get('/budget/upload_budget_str', 'BudgetController@upload_budget_str')->name('budget_str.upload');
Route::POST('/budget/ImportBudget', 'BudgetController@ImportBudget')->name('budget_str.ImportBudget');
Route::POST('/budget/save_budget', 'BudgetController@Save_Budget')->name('budget_str.save_budget');
Route::get('DownloadBudgetHI', 'BudgetController@DownloadBudgetHI')->name('budget_str.DownloadBudgetHI');
Route::get('DownloadBudgetSEI', 'BudgetController@DownloadBudgetSEI')->name('budget_str.DownloadBudgetSEI');

//*** Proposal
//** Eprop ==INPUT SENTRA EPROP
Route::get('/eprop/sentralisasi','EpropSentralController@InputSentra');
Route::POST('/eprop/PostFirstPage','EpropSentralController@PostfirstPage')->name('PostFirstPage');
Route::POST('/eprop/PostSecondMTPage','EpropSentralController@PostSecondMTPage')->name('PostSecondMTPage');
Route::POST('/eprop/PostThridPage','EpropSentralController@PostThridPage')->name('PostThridPage');
Route::POST('/eprop/PostFourthPage','EpropSentralController@PostFourthPage')->name('PostFourthPage');
Route::get('/eprop/EpropCancel','EpropSentralController@CancelledInput')->name('sentra.Cancelled');
//** Previous Button
Route::get('/eprop/prev/SentraPage1','EpropSentralController@SentraPrevPage1')->name('SentraPrevPage1');
Route::get('/eprop/prev/SentraPrevPage2','EpropSentralController@SentraPrevPage2')->name('SentraPrevPage2');
Route::get('/eprop/prev/SentraPrevPage3','EpropSentralController@SentraPrevPage3')->name('SentraPrevPage3');
//Save ** Eproposal
Route::POST('/eprop/SaveEprop','SaveEpropController@SaveEprop')->name('SaveEprop');
//** Proposal dropdown dependent 
Route::get('/eprop/getBrand/{company_id}', 'EpropSentralController@getBrand');
Route::get('/eprop/getVarian/{company_id}', 'EpropSentralController@getVarian');
Route::get('/eprop/getActivity/{division_id}', 'EpropSentralController@getActivity');
Route::get('/eprop/getExecutor/{branch_id}/{market_type}', 'EpropSentralController@getExecutor');
Route::get('/eprop/getVarian/{brand_id}', 'EpropSentralController@getVarian');
Route::get('/eprop/getBudgetBreak/{total_budget}','EpropSentralController@getBudgetBreak');
Route::get('/eprop/target/{activity}','EpropSentralController@getEpropTarget');
//**Search Eprop
Route::get('/eprop/search', 'EpropSentraSearch@index');
Route::POST('/eprop/submit_search', 'EpropSentraSearch@Search')->name('search.eprop');
//**eprop copy == copy eprop
Route::get('/eprop_copy/copy_eprop/{header_id}', 'EpropSentraCopy@CopySentra')->name('copy.eprop');
Route::POST('/eprop_copy/copy_eprop', 'EpropSentraCopy@CPFirstPage')->name('CPFirstPage');
Route::POST('/eprop_copy/copy_eprop2', 'EpropSentraCopy@CPostSecondMTPage')->name('CPostSecondMTPage');
Route::POST('/eprop_copy/copy_eprop3', 'EpropSentraCopy@CPostThridPage')->name('CPostThridPage');
Route::get('/eprop_copy/copy_EpropCancel','EpropSentraCopy@CancelledInput')->name('copy.sentra.Cancelled');
//** eprop copy Previous Button
Route::get('/eprop_copy/prev/CSentraPage1','EpropSentraCopy@CPSentraPrevPage1')->name('copy.SentraPrevPage1');
Route::get('/eprop_copy/prev/CSentraPrevPage2','EpropSentraCopy@CPSentraPrevPage2')->name('copy.SentraPrevPage2');
Route::get('/eprop_copy/prev/CSentraPrevPage3','EpropSentraCopy@CPSentraPrevPage3')->name('copy.SentraPrevPage3');
