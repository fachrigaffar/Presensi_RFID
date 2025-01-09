<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceApiController;
use App\Http\Controllers\StudentApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/attendances', [AttendanceApiController::class, 'index']);
Route::post('/attendance', [AttendanceApiController::class, 'store']);
Route::get('/attendance', function () {
    return response()->json(['message' => 'Use POST to send attendance data.'], 405);
});

Route::get('/students', [StudentApiController::class, 'index']);
Route::post('/register', [StudentApiController::class, 'store']);
Route::get('/register', function () {
    return response()->json(['message' => 'Use POST to register a student.'], 405);
});




