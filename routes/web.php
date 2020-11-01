<?php

use App\Http\Controllers\VideoChatController;
use Illuminate\Support\Facades\Route;
use Facade\FlareClient\Stacktrace\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Response;


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

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
  Route::get('/home/join/{randId}', [VideoChatController::class, 'index']);
  Route::post('/home/join/auth', [VideoChatController::class, 'auth']);
  Route::post('/home/join/', [VideoChatController::class, 'GotoRoom']);
});

Route::get('/storage/{filename}', function($filename) {
$storagePath = storage_path('app/public/'.$filename);

            //  return Response::make($storagePath)->response();
            return response()->file($storagePath);
});