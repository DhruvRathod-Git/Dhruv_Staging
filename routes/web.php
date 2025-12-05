<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\facades\Cookie;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DropdownController;
    
// Route::get('/', [UsersController::class, 'dashboard'])->name('users.dashboard');
Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::get('/auth/register', [RegisterController::class, 'index'])->name('auth.register');
Route::post('/auth/store', [RegisterController::class, 'store'])->name('auth.store');
Route::post('/auth/stores', [LoginController::class, 'stores'])->name('auth.stores');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('sanctum')->group(function () {

    Route::middleware(['admin:admin'])->group(function(){
        Route::get('/auth/homes', [LoginController::class, 'homes'])->name('auth.homes');
    });

    Route::middleware(['user:user'])->group(function(){
        Route::get('/auth/registerhome', [RegisterController::class, 'home'])->name('auth.registerhome');
    });
    
    Route::get('/user/index', [UsersController::class, 'index'])->name('user.index');
    Route::post('/user/store', [UsersController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UsersController::class, 'edit'])->name('user.edit');
    Route::get('/user/show/{id}', [UsersController::class, 'show'])->name('user.show');
    Route::post('/user/update/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::post('/user/fetchCountry', [UsersController::class, 'fetchCountry'])->name('user.fetchCountry');
    Route::post('/user/fetchState', [UsersController::class, 'fetchState'])->name('user.fetchState');
    Route::post('/user/fetchCity', [UsersController::class, 'update'])->name('user.fetchCity');
    Route::delete('/user/{id}', [UsersController::class, 'destroy'])->name('user.destroy');

    Route::get('/des/index', [DesignationController::class, 'index'])->name('des.index');
    Route::get('/des/create', [DesignationController::class, 'create'])->name('des.create');
    Route::post('/des/store', [DesignationController::class, 'store'])->name('des.store');
    Route::get('/des/show/{id}', [DesignationController::class, 'show'])->name('des.show');
    Route::get('/des/edit/{id}', [DesignationController::class, 'edit'])->name('des.edit');
    Route::post('/des/update/{id}', [DesignationController::class, 'update'])->name('des.update');
    Route::delete('/des/{id}', [DesignationController::class, 'destroy'])->name('des.destroy');
    
    Route::get('/dep/index', [DepartmentController::class, 'index'])->name('dep.index');
    Route::get('/dep/create', [DepartmentController::class, 'create'])->name('dep.create');
    Route::post('/dep/store', [DepartmentController::class, 'store'])->name('dep.store');
    Route::get('/dep/show/{id}', [DepartmentController::class, 'show'])->name('dep.show');
    Route::get('/dep/edit/{id}', [DepartmentController::class, 'edit'])->name('dep.edit');
    Route::post('/dep/update/{id}', [DepartmentController::class, 'update'])->name('dep.update');
    Route::delete('/dep/{id}', [DepartmentController::class, 'destroy'])->name('dep.destroy');
    
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/todo/index', [TodoController::class, 'index'])->name('todo.index');
    Route::get('/todo/create', [TodoController::class, 'create'])->name('todo.create');
    Route::post('/todo/store', [TodoController::class, 'store'])->name('todo.store');
    Route::get('/todo/show/{id}', [TodoController::class, 'show'])->name('todo.show');
    Route::get('/todo/edit/{id}', [TodoController::class, 'edit'])->name('todo.edit');
    Route::post('/todo/update/{id}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');

    Route::get('att/index', [AttendanceController::class, 'index'])->name('att.index');
    Route::post('/att/store', [AttendanceController::class, 'store'])->name('att.store');
    Route::get('/att/show/{id}', [AttendanceController::class, 'show'])->name('att.show');
    Route::get('/att/edit/{id}', [AttendanceController::class, 'edit'])->name('att.edit');
    Route::post('/att/update/{id}', [AttendanceController::class, 'update'])->name('att.update');
    Route::delete('/att/{id}', [AttendanceController::class, 'destroy'])->name('att.destroy');
    Route::post('/att/checkIn', [AttendanceController::class, 'checkIn'])->name('att.checkIn');
    Route::post('/att/checkOut', [AttendanceController::class, 'checkOut'])->name('att.checkOut');

    Route::get('/leave/index', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    Route::delete('/leave/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});