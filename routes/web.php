<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PagesAddController;
use App\Http\Controllers\PagesEditController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioAddController;
use App\Http\Controllers\PortfolioEditController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceAddController;
use App\Http\Controllers\ServiceEditController;


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

Route::group([], function()  {

        Route::get('/', [IndexController::class, 'index'])->name('home');
        Route::post('/', [IndexController::class,'sendMail'])->name('mail');
        Route::get('/page/{alias}', [PageController::class, 'execute'])->name('page');
        Route::auth();
});

//admin/service
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function () {

    //admin
    Route::get('/', function () {

        if (view()->exists('admin.index')) {
            $data = ['title' => 'Панель администратора'];

            return view('admin.index',$data);
        }
    });

    //admin/pages

    Route::group(['prefix'=>'pages'], function () {
    //admin/pages

        Route::get('/', [PagesController::class, 'execute'])->name('pages');
    
        //admin/pages/add
        Route::match(['get', 'post'], '/add', [PagesAddController::class, 'execute'])->name('pagesAdd');
        //admin/edit/{page}
        Route::match(['get', 'post', 'delete'], '/edit/{page}', [PagesEditController::class, 'execute'])->name('pagesEdit');
    });
        
        Route::group(['prefix' => 'portfolios'], function () {
                    
        Route::get('/', [PortfolioController::class, 'execute'])->name('portfolios');

        Route::match(['get', 'post'], '/add', [PortfolioAddController::class, 'execute'])->name('portfolioAdd');
                    
        Route::match(['get', 'post', 'delete'], '/edit/{portfolio}', [PortfolioEditController::class, 'execute'])->name('portfolioEdit');
});

    Route::group(['prefix' => 'services'], function () {

        Route::get('/', [ServiceController::class, 'execute'])->name('services');

        Route::match(['get', 'post'], '/add', [ServiceAddController::class, 'execute'])->name('serviceAdd');

        Route::match(['get', 'post', 'delete'], '/edit/{service}', [ServiceEditController::class, 'execute'])->name('serviceEdit');
    });
});



