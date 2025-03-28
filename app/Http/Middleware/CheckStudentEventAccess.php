<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStudentEventAccess
{
    /**
     * Handle an incoming request.
     * Restrict student abilities: they can only create personal events and invite other students.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply these restrictions to students
        if ($user && $user->isStudent()) {
            // If this is a request to create a new event
            if ($request->is('events/create') || $request->is('events')) {
                // For students, we only restrict based on event type in the controller
                return $next($request);
            }

            // For event editing or deletion, check if the student is the owner
            if ($request->route('event')) {
                $event = $request->route('event');
                
                // If the student is not the creator and it's not a personal event
                if ($event->created_by !== $user->id && $event->type !== 'personal') {
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                        return response()->json([
                            'message' => __('Учні можуть редагувати лише власні персональні події')
                        ], 403);
                    }
                    
                    return redirect()->route('events.index')
                        ->with('error', __('Учні можуть редагувати лише власні персональні події'));
                }
            }
        }

        return $next($request);
    }
}
