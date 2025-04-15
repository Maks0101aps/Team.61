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
            ->with(['creator', 'attachments'])
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
                    'created_by_id' => $event->created_by,
                    'is_content_hidden' => $event->is_content_hidden,
                    'attachments' => $event->attachments->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'original_filename' => $attachment->original_filename,
                            'mime_type' => $attachment->mime_type,
                            'size' => $attachment->size,
                            'download_url' => route('events.attachments.download', [$attachment->event_id, $attachment->id]),
                        ];
                    }),
                ];
            });

        return Inertia::render('Dashboard/Index', [
            'tasks' => $tasks,
            'events' => $events,
            'language' => session('language', 'uk'),
        ]);
    }
}
