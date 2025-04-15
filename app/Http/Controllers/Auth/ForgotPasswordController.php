<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ForgotPasswordController extends Controller
{
    /**
     * Показать форму для запроса сброса пароля
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Отправить ссылку на сброс пароля на email пользователя
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            // Удаляем старые токены для этого пользователя
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            // Создаем новый токен
            $token = Str::random(64);

            // Сохраняем токен в базе
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Отправляем уведомление со ссылкой для сброса пароля
            $user->notify(new \App\Notifications\ResetPassword($token, $user->first_name));

            return back()->with('status', 'Ссылка для сброса пароля отправлена на вашу почту!');
        }

        return back()->withErrors([
            'email' => 'Не удалось отправить ссылку для сброса пароля.'
        ]);
    }
}
