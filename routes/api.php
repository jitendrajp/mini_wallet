<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [PasswordController::class, 'sendResetLink']);
Route::post('reset-password', [PasswordController::class, 'reset']);
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
        return redirect('/email-status?status=invalid');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('/email-status?status=already');
    }

    $user->markEmailAsVerified();
    event(new Verified($user));

    return redirect('/email-status?status=success');
})->name('verification.verify');

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [AuthController::class, 'index']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::post('add_balance', [TransactionController::class, 'addBalance']);
});

Route::post('broadcasting/auth', function (Request $request) {
    $user = auth('api')->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $socketId = $request->socket_id;
    $channelName = $request->channel_name;

    $key = env('PUSHER_APP_KEY');
    $secret = env('PUSHER_APP_SECRET');
    $auth = hash_hmac('sha256', $socketId . ':' . $channelName, $secret);

    return response()->json(['auth' => $key . ':' . $auth]);
});

