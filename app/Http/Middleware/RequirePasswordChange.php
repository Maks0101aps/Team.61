<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Если пользователь авторизован и ему требуется сменить пароль
        if ($user && $user->password_change_required) {
            // Если пользователь не на странице смены пароля и не пытается сменить пароль
            if (!$request->is('password/change') && !$request->is('password/update')) {
                return redirect()->route('password.change')
                    ->with('info', __('Для вашої безпеки потрібно змінити тимчасовий пароль.'));
            }
        }
        
        return $next($request);
    }
} 