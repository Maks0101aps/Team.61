<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentRole
{
    /**
     * Handle an incoming request.
     * Prevent students from accessing routes that they should not have access to.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is a student, block access
        if ($request->user() && $request->user()->role === User::ROLE_STUDENT) {
            return redirect()->route('dashboard')->with('error', 'Учні не мають доступу до цієї функції');
        }

        return $next($request);
    }
}
