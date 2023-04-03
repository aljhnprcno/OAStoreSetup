<?php

use Illuminate\Support\Facades\Route;
use App\Services\Users\Acl;

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

Route::get('/', 'Auth\LoginController@index')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'check_auth'], function () {
    Route::middleware(['employee_only'])->group(function () {
        Route::get('/home', 'AdminController@index')->name('home');
    });
    Route::get('/notifications/get', 'NotificationController@get');
    Route::get('/notifications/mark_as_read', 'NotificationController@mark_as_read');
});

Route::prefix('access_rights')->middleware(['check_auth', 'check_permission:' . Acl::PERMISSION_ACCESS_RIGHTS])->group(function () {
    Route::middleware(['admin_and_employee_only'])->group(function () {
        Route::get('/', 'AccessRightController@index');
        Route::get('/get_permissions', 'AccessRightController@getPermissions');
        Route::get('/edit', 'AccessRightController@edit_index');
        Route::post('/get_permission', 'AccessRightController@getPermission');
        Route::post('/add_permission', 'AccessRightController@addPermission');
        Route::post('/revoke_permission', 'AccessRightController@revokePermission');
        Route::post('/delete_permission', 'AccessRightController@deletePermission');
        Route::post('/find_employees', 'EmployeeController@getFilteredEmployees');
    });
});


Route::get('/branch_login/{user_type}/{branch_code}/{user_id}', 'Auth\LoginController@branch_login')->name('branch_login');
Route::post('/login', 'Auth\LoginController@multi_login')->name('user.login');

Route::post('/get_employee_list', 'EmployeeController@get_employees')->name('get_employee_list');

Route::prefix('dashboard')->group(function () {
    Route::get('/qrcode', 'StudentController@qrcode');
    Route::get('/', 'StudentController@student_index');
});

Route::prefix('id_printing')->middleware(['check_auth', 'check_permission:' . Acl::PERMISSION_ID_SETUP])->group(function () {
    Route::middleware(['admin_and_employee_only'])->group(function () {
        Route::get('/id_setup', 'IDPrintingController@setup');
        Route::post('/request', 'IDPrintingController@getIDSetups');
        Route::post('/request/create', 'IDPrintingController@createIDSetup');
        Route::post('/branch/gradelevel', 'IDPrintingController@getGradeLevel');
        Route::post('/branch/student/level', 'IDPrintingController@getGradeLevelByStudent');
        Route::post('/id/delete', 'IDPrintingController@delete');
        Route::post('/id/update', 'IDPrintingController@update');
    });
});
Route::post('/branches', 'IDPrintingController@getBranches');
Route::post('/request/download', 'IDPrintingController@download');

Route::prefix('id_requests')->middleware(['check_auth', 'check_permission:' . Acl::PERMISSION_ID_REQUESTS])->group(function () {
    Route::middleware(['admin_and_employee_only'])->group(function () {
        Route::post('/requests/all', 'IDRequestsController@getAllIDRequests');
        Route::get('/request/lists', 'IDRequestsController@setup');
        Route::post('/request/approve', 'IDRequestsController@approveRequests');
        Route::post('/request/pickup', 'IDRequestsController@pickup');
    });
});

Route::post('/request/print', 'IDRequestsController@print');

Route::prefix('request_setup')->middleware(['check_auth', 'check_permission:' . Acl::PERMISSION_ID_SETUP])->group(function () {
    Route::middleware(['admin_and_employee_only'])->group(function () {
        Route::get('/request-setup', 'RequestSetupController@request_setup');
        Route::post('/request', 'RequestSetupController@CreateSetup');
        Route::post('/request/list', 'RequestSetupController@requestSetupList');
        Route::post('/request/delete', 'RequestSetupController@deleteRequestSetup');
        Route::post('/request/update', 'RequestSetupController@updateRequestSetup');
    });
});


Route::prefix('student_id')->group(function () {
    Route::middleware(['student_only'])->group(function () {
        Route::post('getTemplateByStudent', 'IDPrintingController@getTemplateByStudent');
        Route::post('getqr', 'IDPrintingController@getStudentQr');
    });
    Route::post('request/physical-id', 'IDPrintingController@IdRequestMail');
});

Route::get('/admin/store', 'StoreSetupController@index');
Route::get('/admin/AddProduct', 'StoreSetupController@store');

Route::post('/admin/add', 'StoreSetupController@create');
Route::post('/admin/getproduct', 'StoreSetupController@getProduct');

Route::post('/admin/delete', 'StoreSetupController@deleteProduct');
Route::post('/admin/deletepackage', 'StoreSetupController@deletePackage');
Route::post('/admin/deletecategory', 'StoreSetupController@deleteCategory');

Route::post('/admin/gradelevels', 'StoreSetupController@getGradeLevels');

Route::post('/admin/getpackage', 'StoreSetupController@getPackage');
Route::post('/admin/getcategory', 'StoreSetupController@getCategory');

Route::post('/admin/addpackage', 'StoreSetupController@createPackage');
Route::post('/admin/addcategory', 'StoreSetupController@createCategory');






