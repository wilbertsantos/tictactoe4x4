<?php

use App\Http\Controllers\TicController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tic/{player?}', [TicController::class, 'startGame'])->name('tic.startGame');
Route::get('/tic/{player}/{board}', [TicController::class, 'showBoard'])
->name('tic.showBoard')
->where(['player' => '[XO]', 'board' => '[XO-]{16}']);
Route::get('/tic/{player}/{board}/{position}', [TicController::class, 'playMove'])
->name('tic.playMove')
->where(['player' => '[XO]', 'board' => '[XO-]{16}']);
Route::get('/tic/{player}/winner', [TicController::class, 'showWinner'])
->name('tic.winner')
->where(['player' => '[XO]']);