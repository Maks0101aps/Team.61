<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class VerificationController extends Controller
{
    /**
     * Показать форму для ввода кода верификации
     */
    public function show(Request $request): Response
    {
        $email = $request->session()->get('verification_email');
        $user = auth()->user();
        
        return Inertia::render('Auth/Verify', [
            'email' => $email,
            'userRole' => $user->role,
        ]);
    }

    /**
     * Проверить код верификации
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $email = $request->input('email');
        $code = $request->input('code');

        if (EmailVerification::verifyCode($email, $code)) {
            // Если код верен, обновим статус верификации пользователя
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->email_verified_at = now();
                $user->save();

                // Авторизуем пользователя, если он еще не авторизован
                if (!auth()->check()) {
                    auth()->login($user);
                }

                return redirect()->route('dashboard')->with('success', 'Email успешно подтвержден!');
            }
        }

        return back()->withErrors([
            'code' => 'Неверный код подтверждения или срок его действия истек.'
        ]);
    }

    /**
     * Повторно отправить код верификации
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user && is_null($user->email_verified_at)) {
            // Генерируем новый код и отправляем его
            $code = EmailVerification::generateCode($email);
            $user->notify(new \App\Notifications\VerifyEmail($code, $user->first_name));

            return back()->with('status', 'Новый код подтверждения отправлен на вашу почту!');
        }

        return back()->withErrors([
            'email' => 'Не удалось отправить код подтверждения.'
        ]);
    }
}
