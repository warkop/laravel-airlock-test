<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::middleware('auth:airlock')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:airlock')->get('/revoke-token', function (Request $request){
    $user = $request->user();
    $user->tokens()->where('id', $user->id)->delete();
});

Route::post('/airlock/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::get('profile', 'API\ProfileController@profile');
Route::put('profile', 'API\ProfileController@updateProfile');
Route::post('change-password', 'API\ProfileController@changePassword');
