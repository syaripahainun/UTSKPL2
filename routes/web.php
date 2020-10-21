<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


//Generate Application
$router->get('/key', function () {
    return \Illuminate\Support\Str::random(32);
});

//router user
$router->get('/users', 'UserController@index');
$router->get('/users/{uuid}', 'UserController@show');
$router->post('/users/save', 'UserController@store');
$router->post('/users/update/{uuid}', 'UserController@update');
$router->post('/users/delete/{uuid}', 'UserController@destroy');

//router login
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

//router CSE
$router->get('/cse/companycount/{user_id}', 'DashboardCSEController@companies');
$router->get('/cse/outletcount/{user_id}', 'DashboardCSEController@outlets');
$router->get('/cse/devicecount/{user_id}', 'DashboardCSEController@devices');
$router->get('/cse/transactionscount/{user_id}', 'DashboardCSEController@transactions');
$router->get('/cse/devices/{user_id}', 'LaporanCSEController@index');
$router->get('/cse/companiesƒ/{user_id}', 'LaporanCSEController@show');
$router->get('/cse/outlets/{user_id}', 'LaporanCSEController@showoutlets');
$router->get('/cse/outlet/report/{id}', 'LaporanCSEController@reportoutlet');
$router->get('/cse/outlet/report/sum/{id}', 'LaporanCSEController@sumamount');
//router CSM
$router->get('/csm/companycount', 'DashboardCSMController@companies');
$router->get('/csm/outletcount', 'DashboardCSMController@outlets');
$router->get('/csm/devicecount', 'DashboardCSMController@devices');
$router->get('/csm/transactionscount', 'DashboardCSMController@transactions');
$router->get('/csm/devices', 'LaporanCSMController@index');
$router->get('/csm/companies', 'LaporanCSMController@show');
$router->get('/csm/outlets/', 'LaporanCSMController@showoutlets');
$router->get('/csm/outlet/report/{id}', 'LaporanCSMController@reportoutlet');
$router->get('/csm/outlet/report/sum/{id}', 'LaporanCSMController@sumamount');

//router admin
//company CSE
$router->post('/usercompany/store/{uuid}', 'UserCompanyController@store');
$router->post('/usercompany/save', 'UserCompanyController@save');
$router->get('/company/{uuid}', 'CompanyController@index');
$router->get('/profile/company/{uuid}', 'CompanyController@show');
$router->post('/company/delete/{id}', 'CompanyController@destroy');
$router->get('/company/list/{uuid}', 'CompanyController@list');
//laporan admin
$router->get('/admin/devices', 'LaporanAdminController@index');
$router->get('/admin/companies', 'LaporanAdminController@show');


//router dashboard//

//card
$router->get('/companycount', 'DashboardController@companies');
$router->get('/listcompanies', 'DashboardController@listcompanies');
$router->get('/outletcount', 'DashboardController@outlets');
$router->get('/listoutlets', 'DashboardController@listoutlets');
$router->get('/devicecount', 'DashboardController@devices');
$router->get('/listdevices', 'DashboardController@listdevices');
$router->get('/listusers', 'DashboardController@listusers');
$router->get('/transactioncount', 'DashboardController@transactions');
$router->get('/listtransactions', 'DashboardController@listtransactions');

//chart
$router->get('/sumfinalamount', 'DashboardController@sumfinalamount');
$router->get('/csm/countdevices', 'DashboardCSMController@countcompany');

//router export excell
$router->get('/userexcel', 'ExcelController@userexcel');
$router->get('/export', 'ExcelController@test_page');
