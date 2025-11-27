<?php


use Illuminate\Support\Facades\Route;
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


Route::get('/welcome', function () {
    // get the all classes from the app\Generators namespace


    return  view('welcome');

});


Route::prefix('')
    ->group(function ()
    {
        \App\Helpers\RouteHelper::includedRouteFiles(__DIR__ . '/builder');
    });

    // Route::fallback(function (\Illuminate\Http\Request $request,\Exception $e) {

    //     return view('fallback',compact('e'));
    // });
