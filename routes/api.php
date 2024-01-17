<?php

use App\Http\Controllers\BienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*routes pour les biens trouves*/
Route::post('biens/store', [BienController::class, 'store']);
Route::get('biens/index', [BienController::class, 'index']);
Route::get('biens/{bien}/show', [BienController::class, 'show']);
Route::post('biens/{bien}/update', [BienController::class, 'update']);
Route::delete('biens/{bien}/destroy', [BienController::class, 'destroy']);
Route::post('biens/{bien}/accepte', [BienController::class, 'acceptBien']);
Route::post('biens/{bien}/refuse', [BienController::class, 'refuseBien']);




/*routes pour es utilisateurs*/

Route::post('register', [AuthController::class, 'register']);
Route::post('users/{user}/update', [AuthController::class, 'update']);
Route::get('users/index', [AuthController::class, 'index']);
Route::put('{user}/archive', [AuthController::class, 'archive']);
Route::get('users/archives', [AuthController::class, 'userArchive']);
Route::get('users/nonArchives', [AuthController::class, 'userNonArchive']);


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);


});
