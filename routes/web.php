<?php

use App\Http\Controllers\WeatherController;
use App\Models\WeatherHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/weather');
})->middleware('auth');
Route::get('/weather', [WeatherController::class, 'getIndex'])->middleware('auth');

Route::post('/weather', [WeatherController::class, 'getWeather'])->name('weather.get')->middleware('auth');
Route::get('/weather/view-more', [WeatherController::class, 'viewMore'])->name('weather.viewmore.get');
Route::post('/weather-forecast', [WeatherController::class, 'forecast'])->name('forecast.get')->middleware('auth');
Route::post('/receive-forecast-mail', [WeatherController::class, 'receiveForecastMail'])->name('receive.forecast.mail')->middleware('auth');
Route::get('/unreceive-forecast-mail', [WeatherController::class, 'unReceiveForecastMail'])->name('unreceive.forecast.mail')->middleware('auth');



Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
