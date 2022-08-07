<?php

use App\Http\Controllers\UserController;
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

/*
|--------------------------------------------------------------------------
| Conventions
|--------------------------------------------------------------------------
|
| The consensus seems to be plural routes, singular controller names.
| Still, raise this with the proctor and check with him if they
| already got a project running and are using a different 
| convention. We follow that if that's the case.
|
*/
Route::resource('users', UserController::class)->only([
    'show'
    // we use a resource controller regardless and just filter
    // just to anticipate more routes for this resource.
]);