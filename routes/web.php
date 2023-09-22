<?php

use App\Http\Controllers\ExcelController;
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

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/',[ExcelController::class,'index'])->name('index');
Route::post('/imports',[ExcelController::class,'import'])->name('import');
Route::get('/export',[ExcelController::class,'export'])->name('export');
Route::get('/data',[ExcelController::class,'data'])->name('data');
