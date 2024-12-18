<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'Api', 'middleware' => 'api','prefix' => 'v1'], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'verifiy.token'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

    });

    //Route::post('refresh',  'AuthController@refresh');
    // Route::post('me', 'AuthController@me');
    Route::group(['middleware' => ['verifiy.token', 'change.lang']], function() {

        Route::post('create-cat',  [CategoriesController::class, 'createCat']);
        Route::post('update-cat',  [CategoriesController::class, 'updateCat']);
        Route::post('delete-cat',  [CategoriesController::class, 'deleteCat']);
        Route::post('get-all-cats',  [CategoriesController::class, 'getAllCats']);
        Route::post('get-single-cat',  [CategoriesController::class, 'getSingleCat']);
    });

    

});

