<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('home', ['title' => 'Home']);
})->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('tasks', 'TaskController');
    Route::post('/create-task', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
    Route::get('/tasks', [TaskController::class, 'filter'])->name('tasks.filter');
    Route::post('/tasks-share', [TaskController::class, 'share'])->name('tasks.share');
});

Route::get('/my-tasks', [TaskController::class, 'showTasks'])->name('tasks.showTasks');
Route::get('/sign-in', [UserController::class, 'index'])->name('signIn');
Route::post('/sign-in', [UserController::class, 'signIn']);
Route::get('/sign-up', [UserController::class, 'signUp'])->name('signUp');
Route::post('/sign-up', [UserController::class, 'postSignUp']);
Route::post('/sign-out', [UserController::class, 'signOut'])->name('signOut');




