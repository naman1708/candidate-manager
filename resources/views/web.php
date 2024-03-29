<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateRolesController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleInterviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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
// Route::middleware(['auth', 'role:admin'])->group(function () {
// });


Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});


Route::middleware(['auth', 'verified'])->group(function () {

    // dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // User Profile
    Route::get('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');


    // candidate routes
    Route::get('/candidates/{search?}/{category?}', [CandidateController::class, 'index'])->name('candidates');
    Route::get('/view-candidate/{candidate?}', [CandidateController::class, 'show'])->name('candidate.view');

    Route::get('/add-candidate', [CandidateController::class, 'create'])->name('candidate.add');
    Route::post('/add-candidate', [CandidateController::class, 'store'])->name('candidate.save');

    Route::get('/edit-candidate/{candidate?}', [CandidateController::class, 'edit'])->name('candidate.edit');
    Route::post('/edit-candidate/{candidate?}', [CandidateController::class, 'update'])->name('candidate.update');

    Route::get('/delete-candidate/{candidate?}', [CandidateController::class, 'destroy'])->name('candidate.delete');

    Route::get('/download-resume/{resume?}', [CandidateController::class, 'downloadResume'])->name('download.resume');

    Route::get('import-candidate', [CandidateController::class, 'importFileView'])->name('candidate.importFileView');

    Route::post('import-candidate', [CandidateController::class, 'import'])->name('candidate.import');

    Route::get('export-candidate/', [CandidateController::class, 'export'])->name('candidate.export');
    Route::post('selected-export-candidate/', [CandidateController::class, 'selectedCandidateExport'])->name('candidate.selectedCandidateExport');


    Route::get('download-sampleCsv-candidate', [CandidateController::class, 'downloadSampleCsv'])->name('candidate.downloadSampleCsv');

    Route::post('upload-candidate-resumes/', [CandidateController::class, 'uploadResume'])->name('candidate.uploadResume');

    Route::post('/candidate-edit-instructions/{candidate_id?}', [CandidateController::class, 'candidateAddEditInstructions'])->name('candidateAddEditInstructions');


    Route::get('getSuperadminInstruction/{candidate_id}', [CandidateController::class, 'getSuperadminInstruction'])->name('getSuperadminInstruction');



    // Candidate  Role routes

    Route::get('/candidates-roles/{search?}/', [CandidateRolesController::class, 'index'])->name('candidatesRoles');

    Route::get('/add-candidate-role', [CandidateRolesController::class, 'create'])->name('candidatesRole.add');
    Route::post('/add-candidate-role', [CandidateRolesController::class, 'store'])->name('candidatesRole.save');

    Route::get('/edit-candidate-role/{candidatesRole?}', [CandidateRolesController::class, 'edit'])->name('candidatesRole.edit');
    Route::post('/edit-candidate-role/{candidatesRole?}', [CandidateRolesController::class, 'update'])->name('candidatesRole.update');

    Route::get('/delete-candidate-role/{candidatesRole?}', [CandidateRolesController::class, 'destroy'])->name('candidatesRole.delete');





    // Schedule Interview Controller Route

    Route::post('candidate-schedule-interview', [ScheduleInterviewController::class, 'scheduleInterview'])->name('candidate.scheduleInterview');

    // DashboardController Controller Route
    Route::get('todays-uploaded-candidates', [DashboardController::class, 'currentDateUploadedCandidates'])->name('currentDateUploadedCandidates');
});




    // Information Controllers Route
    Route::get('informations', [InformationController::class, 'informations'])->name('informations');
    Route::get('informations-view/{information?}', [InformationController::class, 'show'])->name('information.show');

    Route::get('informations-add', [InformationController::class, 'create'])->name('information.create');
    Route::post('informations-store', [InformationController::class, 'store'])->name('information.store');

    Route::get('informations-edit/{information?}', [InformationController::class, 'edit'])->name('information.edit');
    Route::post('informations-update{information?}', [InformationController::class, 'update'])->name('information.update');

    Route::get('informations-delete/{information?}', [InformationController::class, 'delete'])->name('information.delete');

    Route::get('informations-user/{user_id?}', [UserController::class, 'usersInformations'])->name('user.info');



require __DIR__ . '/auth.php';
