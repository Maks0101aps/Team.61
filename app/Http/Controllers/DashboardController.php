<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $tasks = Auth::user()->account->tasks()
            ->with('event')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'event' => $task->event->title,
                    'due_date' => $task->due_date->format('Y-m-d H:i:s'),
                    'completed' => $task->completed,
                ];
            });

        $events = Auth::user()->account->events()
            ->with('creator')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'type' => $event->type,
                    'start_date' => $event->start_date->format('Y-m-d H:i:s'),
                    'duration' => $event->duration,
                    'location' => $event->location,
                    'online_link' => $event->online_link,
                    'created_by' => $event->creator->name,
                    'is_content_hidden' => $event->is_content_hidden,
                ];
            });

        return Inertia::render('Dashboard/Index', [
            'tasks' => $tasks,
            'events' => $events,
            'language' => session('language', 'uk'),
        ]);
    }
}
