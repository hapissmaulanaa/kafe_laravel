<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\mejaController;
use App\Http\Controllers\transaksiController;

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

// Route::post('/register', 'UserController@register');
Route::post('/login', [userController::class,'login']);


// User
Route::get('/getuser/{id}',[userController::class,'getuserId']);
Route::get('/getuser',[userController::class,'getUser']);
Route::post('/createuser',[userController::class,'createUser']);
Route::put('/updateuser/{id}',[userController::class,'updateUser']);
Route::delete('/deleteuser/{id}',[userController::class,'deleteUser']);

Route::get('/getkasir',[transaksiController::class,'getkasir']);

// Menu
Route::get('/getmenu/{id}',[menuController::class,'getmenuId']);
Route::get('/getmenu',[menuController::class,'getMenu']);
Route::post('/createmenu',[menuController::class,'createMenu']);
Route::post('/updategambar/{id}',[menuController::class,'updategambar']);
Route::put('/updatemenu/{id}',[menuController::class,'updateMenu']);
Route::delete('/deletemenu/{id}',[menuController::class,'deleteMenu']);

// Meja
Route::get('/getmeja/{id}',[mejaController::class,'getmejaId']);
Route::get('/getmeja',[mejaController::class,'getMeja']);
Route::post('/createmeja',[mejaController::class,'createMeja']);
Route::put('/updatemeja/{id}',[mejaController::class,'updateMeja']);
Route::delete('/deletemeja/{id}',[mejaController::class,'deleteMeja']);

// Transaksi
Route::get('/gethistory',[TransaksiController::class, 'gethistory']);
Route::get('/gethistory/{code}',[TransaksiController::class, 'selecthistory']);

Route::get('/tampil',[TransaksiController::class, 'tampil']);
Route::get('/get_ongoing_transaksi/{id}',[TransaksiController::class, 'getongoingtransaksi']);
Route::get('/gettotalharga/{id}',[TransaksiController::class, 'totalharga']);
Route::get('/gettotal/{code}',[TransaksiController::class,'total']);
Route::get('/getcart',[TransaksiController::class, 'getcart']);
Route::get('/getongoing',[TransaksiController::class, 'ongoing']);
Route::put('/checkout',[TransaksiController::class, 'checkout']);
Route::put('/transaksidone/{id}',[TransaksiController::class, 'transaksidone']);
Route::get('/gettransaksi/{id}',[TransaksiController::class, 'selecttransaksi']);
Route::post('/createtransaksi',[TransaksiController::class, 'createtransaksi']);

Route::delete('/deletetransaksi/{id}',[TransaksiController::class, 'deletetransaksi']);

Route::get('/gettgl/{date}',[TransaksiController::class,'gettgl']);
Route::get('/getbulan/{month}',[TransaksiController::class,'getbulan']);