<?php








use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HopitalController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DonationController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/bannir/{id}',[AdminController::class,'bannir'])->middleware('auth:sanctum');
Route::post('/debannir/{id}',[AdminController::class,'débannir'])->middleware('auth:sanctum');
Route::post('/login',[AuthController::class,'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;
Route::post('/storeHospital',[AdminController::class,'createHopital'])->middleware('auth:sanctum');;
Route::post('/storeCentre',[AdminController::class,'createCentre'])->middleware('auth:sanctum');
Route::get('/indexHospital',[AdminController::class,'listHopitals'])->middleware('auth:sanctum');
Route::get('/indexCentre',[AdminController::class,'listCenters'])->middleware('auth:sanctum');
Route::delete('/centre/{id}',[AdminController::class,'supprimerCentre'])->middleware('auth:sanctum');
Route::delete('/hospital/{id}',[AdminController::class,'supprimerHopital'])->middleware('auth:sanctum');
Route::get('/users',[AdminController::class,'listUsers'])->middleware('auth:sanctum');
Route::post('/envoi', [HopitalController::class,"createrequest"])->middleware('auth:sanctum');
Route::get('/index', [CenterController::class,"voirRequest"])->middleware('auth:sanctum');
Route::get('/valider/{id}', [CenterController::class,"validerRequest"])->middleware('auth:sanctum');
Route::get('notificationDonor', [NotificationController::class,"DonorNotifications"])->middleware('auth:sanctum');
Route::get('getCenterNotification', [NotificationController::class,"getCenterNotifications"])->middleware('auth:sanctum');
Route::post('repondDonor/{id}', [NotificationController::class,"respondDonor"])->middleware('auth:sanctum');



Route::apiResource('donationdonor', DonationController::class)->middleware('auth:sanctum');

Route::get('profile/{id}', [UserController::class,"showProfile"]);
