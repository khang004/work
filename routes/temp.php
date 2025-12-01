<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/reset-admin-password', function() {
    $user = User::where('email', 'admin@hub.com')->first();
    
    if ($user) {
        $user->password = Hash::make('admin2233');
        $user->save();
        return 'Password đã được reset thành công! Email: admin@hub.com | Password: admin2233';
    }
    
    return 'Không tìm thấy user';
});
