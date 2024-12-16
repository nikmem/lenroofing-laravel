<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\{AuthController, EstimateController, EstimateItemController, NotificationController, DashboardController};
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\{FileController};


Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');


Route::group(['prefix' => 'auth'], function () {
	Route::post('login', [AuthController::class, 'login']);
	Route::get('verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
	Route::post('resend-otp', [AuthController::class, 'resendOtp']);
	Route::post('signup', [AuthController::class, 'signup']);
	Route::group(['middleware' => 'auth:api'], function () {
		Route::post('logout', [AuthController::class, 'logout']);
		Route::get('user', [AuthController::class, 'user']);
		Route::post('profile-update', [AuthController::class, 'profileUpdate']);
	});
});

Route::group(['prefix' => 'password'], function () {
	Route::post('forgot', [PasswordController::class, 'forgot']);
	Route::post('reset', [PasswordController::class, 'reset']);
});

Route::group(['prefix' => '/file'], function () {
	Route::post('/analyze', [FileController::class, 'analyze']);
});
Route::group(['prefix' => '/file'], function () {
	Route::post('/upload', [FileController::class, 'upload']);
});

Route::group(['middleware' => ['auth:api']], function () {

	Route::get('/dashboard', [DashboardController::class, 'index']);

	Route::group(['prefix' => '/notification'], function () {
		Route::get('/list', [NotificationController::class, 'list']);
	});
	Route::group(['prefix' => '/estimate'], function () {
		Route::get('/list', [EstimateController::class, 'list']);
		Route::get('/recordings/{id}', [EstimateController::class, 'recordings']);
		Route::get('/show/{id}', [EstimateController::class, 'show']);
		Route::post('/store', [EstimateController::class, 'store']);
		Route::post('/update/{id}', [EstimateController::class, 'update']);
		Route::get('/delete/{id}', [EstimateController::class, 'destroy']);
		Route::get('/mark-urgent/{id}', [EstimateController::class, 'markUrgent']);
		Route::get('/mark-not-urgent/{id}', [EstimateController::class, 'markNotUrgent']);
		Route::get('/mark-archived/{id}', [EstimateController::class, 'markArchived']);
		Route::get('/mark-unarchived/{id}', [EstimateController::class, 'markUnarchived']);
		Route::get('/mark-complete/{id}', [EstimateController::class, 'markComplete']);
		Route::get('/mark-incomplete/{id}', [EstimateController::class, 'markInComplete']);
		Route::get('/search/{query}', [EstimateController::class, 'search']);

	});

	Route::group(['prefix' => '/estimate/item'], function () {
		Route::get('/list', [EstimateItemController::class, 'list']);
		Route::get('/show/{id}', [EstimateItemController::class, 'show']);
		Route::post('/store', [EstimateItemController::class, 'store']);
		Route::post('/update/{id}', [EstimateItemController::class, 'update']);
		Route::get('/delete/{id}', [EstimateItemController::class, 'destroy']);
	});

	Route::group(['prefix' => '/password'], function () {
		Route::post('/change', [PasswordController::class, 'change']);
	});

});
