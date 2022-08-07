<?php

use App\Http\Controllers\UserCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::resource('users.comments', UserCommentController::class)->only([
        'store'
        // we use a resource controller regardless and just filter
        // just to anticipate more routes for this resource.
    ]);

    Route::post('comments', [UserCommentController::class, 'storeWithLegacyRequest']);
});
