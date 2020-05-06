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


use App\Activity;
use App\ControlNumber;
use App\Http\Resources\Dashboard;
use App\Jobs\UpdateDashboardJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', function () {
    if (\auth()->user()->role->name!='admin'){
        return view('layouts.main');
    }
    return redirect('/dashboard');
})->name('home');

Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');


Route::get('/notify',function(){
    dispatch(new UpdateDashboardJob());
});

/*
 * Initialize Constant Table data *
 */
Route::get('/init','SeedController@init');
//Route::get('/seed/project','SeedController@seed');
//Route::get('/seed/locality','SeedController@locality');
//Route::get('/seed/block','SeedController@block');
//Route::get('/seed/plot/{project}','SeedController@plot');
//Route::get('/seed/sale/{project}','SeedController@sale');
//Route::get('/seed/payment/','SeedController@payment');

/*
 * ROUTES
 */
Route::group(['middleware'=>['auth','suspended']],function (){
    Route::middleware(['admin'])->get('/dashboard',function (){
        $dashboard = new Dashboard();
        $data = $dashboard->data;
        $userLogs = $dashboard->userLogs;
        $otherLogs = $dashboard->otherLogs;

        $projects = \App\Project::all()->count();
        $plots = \App\Plot::all()->count();
        $sold = \App\Plot::where('status_id',2)->count();
        $available = $plots-$sold;

        $cards = compact('projects','plots','sold','available');

        return view('dashboard',compact('data','userLogs','otherLogs','cards'));
    })->name('dashboard');
    //Project routes
    Route::resource('/project','ProjectController');
    Route::get('delete/project/{id}','ProjectController@deleteProject')->name('delete.project');
    Route::get('show/project/plots/{slug}','ProjectController@showPlots')->name('project.plots');

    //Locality routes
    Route::resource('/locality','LocalityController');
    Route::post('/get/locality','LocalityController@getLocalities')->name('locality.get');
    Route::get('/delete/locality/{slug}','LocalityController@deleteLocality')->name('locality.delete');
    Route::post('/update/locality','LocalityController@updateLocality')->name('update.locality');
    Route::get('show/locality/plots/{slug}','LocalityController@showPlots')->name('locality.plots');

    //Block routes
    Route::resource('/block','BlockController');
    Route::post('/update/block','BlockController@updateBlock')->name('update.block');
    Route::get('/delete/block/{slug}','BlockController@deleteBlock')->name('block.delete');
    Route::post('/get/block','BlockController@getBlocks')->name('block.get');
    Route::get('show/block/plots/{slug}','BlockController@showPlots')->name('block.plots');


    Route::resource('/plot','PlotController');
    Route::post('/get/plot','PlotController@getPlots')->name('plot.get');
    Route::post('/get/create/plot','PlotController@getPlotsWhenCreate')->name('plot.create.get');
    Route::get('delete/plot/{id}','PlotController@deletePlot')->name('delete.plot');


    Route::resource('/user','UserController')->except('show');
    Route::get('/user/permission/{slug}','UserController@permission')->name('user.permission');
    Route::get('/user/permission/print/{slug}','UserController@printPermission')->name('user.permission.print');
    Route::post('/user/permission/grant/{slug}','UserController@grant')->name('user.grant');
    Route::get('/user/change/password/{slug}','UserController@password')->name('user.password');
    Route::post('/user/change/password/{slug}','UserController@changePassword')->name('user.password.change');
    Route::get('/user/reset/password/{slug}','UserController@resetPassword')->name('user.password.reset');


    Route::resource('/sale','SaleController');
    Route::get('/view/payment/{number}','SaleController@showPayment')->name('sale.payment');
    Route::post('/receive/payment/{number}','SaleController@receive')->name('sale.payment.receive');
    Route::post('/edit/payment/{payment}','SaleController@editPayment')->name('sale.payment.edit');
    Route::get('/print/payment/{id}','SaleController@printReceipt')->name('sale.receipt');
    Route::get('/print/clearance/{number}','SaleController@printClearance')->name('sale.clearance');
    Route::any('/search/sale','SaleController@search')->name('sale.search');
    Route::get('/defaulter/{period}','SaleController@defaulter')->name('sale.defaulter');
    Route::get('/defaulter/revoke/{number}','SaleController@revoke')->name('sale.revoke');
    Route::post('/defaulter/torelate/{number}','SaleController@torelate')->name('sale.torelate');
    Route::get('/view/suspence','SaleController@suspence')->name('sale.suspence');
    Route::any('/search/suspence','SaleController@searchSuspence')->name('sale.suspence.search');
    Route::get('/transfer/money/from/{suspence}','SaleController@transfer')->name('sale.suspence.transfer.from');
    Route::get('/transfer/money/to/{number}','SaleController@transferTo')->name('sale.suspence.transfer.to');
    Route::get('/transfer/cancel','SaleController@cancelTransfer')->name('sale.suspence.transfer.cancel');


    Route::get('/constant','ConstantController@index')->name('constant.index');
    Route::post('/constant','ConstantController@store')->name('constant.store');
    Route::get('/constant/{id}/edit','ConstantController@edit')->name('constant.edit');
    Route::post('/constant/{id}','ConstantController@update')->name('constant.update');

    Route::any('/log','LogController@index')->name('log.index');
});

/*
 * Authentication routes
 */
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );

Auth::routes([
        'register' => false, // Registration Routes...
        'reset' => false, // Password Reset Routes...
        'verify' => false, // Email Verification Routes...
]);
