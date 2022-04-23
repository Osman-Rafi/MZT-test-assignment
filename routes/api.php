<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/get-all-companies-list', [CompanyController::class, 'getAllCompanies']);
Route::get('/get-candidates-list-and-coins/{company}', [CompanyController::class, 'getAllCandidates']);
Route::post('/contact-with-candidate', [CandidateController::class, 'contactWithCandidate']);
Route::post('/hire-candidate', [CandidateController::class, 'hireCandidate']);
