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
    Route::middleware('auth.statickey')->group(function () {
        Route::resource('users.comments', UserCommentController::class)->only([
            'store'
            // we use a resource controller regardless and just filter
            // just to anticipate more routes for this resource.
        ]);
    });

    // Note that we don't use the middleware here as the original implementation checks
    // for input first before the password. If we want 1:1 implem, we can't include this
    // in the middleware wrap.
    Route::post('comments', [UserCommentController::class, 'storeWithLegacyRequest']);
});
