<?php

use App\Http\Controllers\Module\ModuleModificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\ModifierController;
use App\Http\Controllers\ModuleDbController;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\Module\LayoutController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\RolePermissionController;

/*
|--------------------------------------------------------------------------
| builder Routes
|--------------------------------------------------------------------------
|
| Here is where we developed the builder routes for the application.
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Auth::routes();


Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:dashboard']], function () {
        
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | Role Permission Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:role_permissions']], function () {

        Route::get('/get_role', [RolePermissionController::class, 'get_role'])->name('get_role');
        Route::resource('role_permission', RolePermissionController::class)->names('role_permission');
    });

    Route::group(['middleware' => ['permission:system_users']], function () {
        Route::get('/get_user',[UserController::class,'get_user'])->name('get_user');
        Route::resource('users', UserController::class)->names('users');
    });

    Route::group(['middleware' => ['permission:builder']], function () {
        /*
        |--------------------------------------------------------------------------
        | Builder Routes
        |--------------------------------------------------------------------------
        */
        Route::controller(BuilderController::class)->group(function () {
            Route::get('builder', 'builder')->name('builder');
            Route::get('field-template', 'fieldTemplate')->name('field-template');
            Route::get('relation-field-template', 'relationFieldTemplate')->name('relation-field-template');
            Route::post('generate', 'generate')->name('generate');
        });

        /*
        |--------------------------------------------------------------------------
        | Modify Routes
        |--------------------------------------------------------------------------
        */
        Route::controller(ModifierController::class)->group(function () {
            // Modify Module
            Route::get('modify-field/{name}', 'modifyField')->name('modify-field');
            Route::post('modify-migration', 'modifyMigration')->name('modify-migration');
            Route::post('delete-migration', 'deleteMigration')->name('delete-migration');

            Route::post('modify', 'modification')->name('modify');
        });

        Route::controller(GeneratorController::class)->group(function () {
            Route::post('generate', 'generate')->name('generate');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Module Customization Routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => ['permission:modules_config']], function () {

        Route::controller(ModuleController::class)->group(function () {

            Route::get('modules', 'index')->name('module');

            Route::get('modules/customize/{module}/info', 'info')->name('module.customize.info');
            Route::post('modules/customize/info', 'customizeInfo')->name('module.customize.saveInfo');
            Route::post('modules/customize/fields', 'customizeFields')->name('module.customize.saveFields');
            Route::post('modules/customize/{module}/add_validation', 'add_validation')->name('module.customize.add_validation');
            Route::post('modules/customize/{module}/del_validation', 'del_validation')->name('module.customize.del_validation');
            Route::post('modules/delete/all', 'deleteAll')->name('module.delete.all');
            Route::get('modules/delete/{module}', 'deleteByModule')->name('module.deletemodule');
            Route::get('modules/empty/{module}', 'trucateByModule')->name('module.truncatemodule');
        });

        Route::controller(ModuleDbController::class)->group(function () {
            Route::get('/modules/{module}/database', 'index')->name('module.db.index');
            Route::post('/modules/mutant_fields/modify','mutantModify')->name('module.db.modify');
        });

        Route::controller(ModuleModificationController::class)->group(function(){
            Route::post('modules/addIndex','addIndex')->name('module.addIndex');
        });

        Route::controller(LayoutController::class)->group(function () {
            Route::get('modules/layout/{module}', 'index')->name('module.layout.index');
            Route::post('modules/layout/action', 'applyActionLayout')->name('module.layout.action');

        });
    });
    Route::get('get-columns/{tableName}', function ($tableName) {
        $columns = get_columns($tableName);

        return response()->json(['columns' => $columns]);
    })->name('get-columns');

});
