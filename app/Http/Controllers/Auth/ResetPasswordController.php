<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    /**
     * Показать форму для сброса пароля
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
            'email' => $request->email
        ]);
    }

    /**
     * Сбросить пароль
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Проверяем токен
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$tokenData) {
            return back()->withErrors([
                'email' => 'Недействительный токен для сброса пароля или истек срок его действия.'
            ]);
        }

        // Обновляем пароль пользователя
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $user->password = $password;
            $user->save();

            // Удаляем использованный токен
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            // Авторизуем пользователя
            auth()->login($user);

            return redirect()->route('dashboard')->with('success', 'Пароль успешно сброшен!');
        }

        return back()->withErrors([
            'email' => 'Не удалось сбросить пароль.'
        ]);
    }
}
