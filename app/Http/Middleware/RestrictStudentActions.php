<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RestrictStudentActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Якщо користувач - учень, забороняємо створення певних сутностей
        if ($user && $user->isStudent()) {
            // Перевіряємо, чи це запит на створення сутності
            if ($this->isRestrictedStudentRequest($request)) {
                return redirect()->route('dashboard')->with('error', 'Учням не дозволено створювати ці записи в системі.');
            }
        }
        
        // Якщо користувач - батько, забороняємо створення певних сутностей
        if ($user && $user->isParent()) {
            if ($this->isRestrictedParentRequest($request)) {
                return redirect()->route('dashboard')->with('error', 'Батьки можуть створювати тільки своїх дітей.');
            }
        }

        return $next($request);
    }

    /**
     * Визначає, чи є поточний запит обмеженим для учня
     */
    private function isRestrictedStudentRequest(Request $request): bool
    {
        // Обмежені URL для учнів
        $restrictedUrls = [
            'students',
            'parents',
            'teachers',
            'tasks',
        ];
        
        // Перевіряємо URL і метод запиту
        if ($request->isMethod('post')) {
            foreach ($restrictedUrls as $url) {
                if ($request->is($url) || $request->is($url.'/*')) {
                    return true;
                }
            }
        }
        
        // Перевіряємо URL для сторінок створення
        if ($request->isMethod('get')) {
            foreach ($restrictedUrls as $url) {
                if ($request->is($url.'/create')) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Визначає, чи є поточний запит обмеженим для батьків
     */
    private function isRestrictedParentRequest(Request $request): bool
    {
        // Обмежені URL для батьків
        $restrictedUrls = [
            'teachers',
            'parents',
            'events',
            'tasks',
        ];
        
        // Перевіряємо URL і метод запиту
        if ($request->isMethod('post')) {
            foreach ($restrictedUrls as $url) {
                if ($request->is($url) || $request->is($url.'/*')) {
                    return true;
                }
            }
        }
        
        // Перевіряємо URL для сторінок створення
        if ($request->isMethod('get')) {
            foreach ($restrictedUrls as $url) {
                if ($request->is($url.'/create')) {
                    return true;
                }
            }
        }
        
        return false;
    }
} 