<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckParentEventAccess
{
    /**
     * Handle an incoming request.
     * Restrict parents from creating events.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for parent role
        if ($request->user() && $request->user()->role === User::ROLE_PARENT) {
            // If the request is for creating an event (GET/POST) or Ajax request for calendar
            if ($request->is('events/create') || $request->is('events') || $request->ajax()) {
                // For AJAX requests (like when clicking on calendar)
                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Батьки не можуть створювати або змінювати події'
                    ], 403);
                }
                
                // For regular requests
                return redirect()->route('events.index')
                    ->with('error', 'Батьки не можуть створювати або змінювати події');
            }
        }

        return $next($request);
    }
}
