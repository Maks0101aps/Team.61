<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ChangePasswordController extends Controller
{
    /**
     * Показывает форму для смены пароля при первом входе
     */
    public function showChangeForm()
    {
        return Inertia::render('Auth/ChangePassword', [
            'user' => Auth::user()->only('first_name', 'last_name', 'email')
        ]);
    }
    
    /**
     * Обрабатывает запрос на смену пароля
     */
    public function change(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);
        
        $user = Auth::user();
        
        // Проверка текущего пароля
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => __('Текущий пароль указан неверно.'),
            ]);
        }
        
        // Обновление пароля
        $user->password = $request->password;
        $user->password_change_required = false;
        $user->save();
        
        return redirect()->route('dashboard')->with('success', __('Пароль успешно изменен.'));
    }
} 