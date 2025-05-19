<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Navigate the Admin Component
 */
// Route::get('/auth/login',[App\Http\Controllers\Auth\AuthenticationController::class,'index'])->middleware(['web','throttle:60,1'])->name('login');

Route::get('/', [App\Http\Controllers\Auth\AuthenticationController::class,'index'])->middleware(['web','throttle:60,1'])->name('login');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class,'login'])->middleware(['web','throttle:60,1']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class,'logout'])->middleware(['web','throttle:60,1']);


Route::get('/admin',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/dashboard',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/request/details',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/print/request-for-assistance-receipt/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/approve/client',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/approve/client/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/approve/approve-qr',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/approve/bulk',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/claim/client',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/claim/client/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/claim/bulk',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/claim/claim-qr',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/client/information',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/client/information/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/client/information/{id}/edit',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/client/new-transaction',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/client/new-transaction/{id}/beneficiary',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
// Route::get('/client/new-transaction/{id}/beneficiary/submit',[App\Http\Controllers\AdminController::class,'index']);
Route::get('/transactions/client-transactions',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/transactions/client-transaction/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);

Route::get('/print/request-for-assistance-receipt',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/transactions/client-transactions/{id}/beneficiary/{id2}/client/{id3}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/transactions/client-transactions/{id}/client/{id2}/add-beneficiary',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/transactions/report',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/transactions/bulk-printing',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/print/bulk-printing-of-receipt-holder',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/edit-return-days',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/add-permissions',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/add-permissions/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/users',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/edit-user/{id}',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/maintenance/add-user',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/sms/send-sms/to-claim',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);
Route::get('/sms/sms-message',[App\Http\Controllers\AdminController::class,'index'])->middleware(['auth','throttle:60,1']);

//Webhook

Route::get('/sms/webhook',[App\Http\Controllers\SMS\SMSWebhookController::class,'SMSWebHook']);


use Illuminate\Support\Facades\Redis;

Route::get('/redis-check', function () {
    Redis::set('ping', 'pong');
    return Redis::get('ping');
});
