<?php


use Illuminate\Support\Facades\Route;

use App\Models\ListModel;

// Route::get('/test-lists', function () {
//     $lists = ListModel::get(); // soft-deleted yahan nahi aayenge

//     return response()->json($lists);
// });

Route::get('/', function () {
    return view('home');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('regsiter');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('auth.dashboard');
});

Route::get('/list-workspace', function () {
    return view('auth.list-workspace');
});

Route::get('/session/{session}', function ($sessionId) {
    return view('auth.session', ['sessionId' => $sessionId]);
});


// Route::get('/session-view/{session}',[UserController::class,'viewSession']);

Route::get('/session-view/{id}', function ($id) {
    return view('auth.session-view', ['sessionId' => $id]);
});
