<?php

use App\Http\Controllers\InvestorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Models\Investor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('login');
});


// Admin Routes
Route::middleware(['auth','role:admin'])->group(function() {

});   


Route::middleware(['auth','verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // User Profile  
    Route::get('/logout', function( Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');


    // Informations routes
    Route::get('/informations', [InformationController::class, 'index'])->name('informations');
    Route::get('/view-informations/{informations?}', [InformationController::class, 'show'])->name('informations.view');

    Route::get('/add-informations', [InformationController::class, 'create'])->name('informations.add');
    Route::post('/add-informations', [InformationController::class, 'store'])->name('informations.save');

    Route::get('/edit-informations/{informations?}', [InformationController::class, 'edit'])->name('informations.edit');
    Route::post('/edit-informations', [InformationController::class, 'update'])->name('informations.update');

    Route::get('/delete-informations/{informations?}',[InformationController::class , 'destroy'])->name('informations.delete');

    Route::get('/download-resume/{resume}', [InformationController::class, 'downloadResume'])->name('download.resume');

});


require __DIR__.'/auth.php';