<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TasksController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'filters' => Request::all('search', 'trashed'),
            'tasks' => Auth::user()->account->tasks()
                ->with('event', 'creator')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($task) => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'event' => $task->event->title,
                    'due_date' => $task->due_date->format('Y-m-d H:i'),
                    'completed' => $task->completed,
                    'created_by' => $task->creator->name,
                    'deleted_at' => $task->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'events' => Auth::user()->account->events()
                ->orderBy('title')
                ->get()
                ->map
                ->only('id', 'title'),
            'students' => Auth::user()->account->contacts()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'parents' => Auth::user()->account->parents()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'teachers' => Auth::user()->account->teachers()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        $validated = Request::validate([
            'event_id' => ['required', 'exists:events,id'],
            'title' => ['required', 'max:100'],
            'content' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'students' => ['array'],
            'students.*' => ['exists:contacts,id'],
            'teachers' => ['array'],
            'teachers.*' => ['exists:teachers,id'],
            'parents' => ['array'],
            'parents.*' => ['exists:parent_models,id'],
        ]);

        $task = Auth::user()->account->tasks()->create([
            'event_id' => $validated['event_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'due_date' => $validated['due_date'],
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['students'])) {
            $task->students()->attach($validated['students']);
        }

        if (!empty($validated['teachers'])) {
            $task->teachers()->attach($validated['teachers']);
        }

        if (!empty($validated['parents'])) {
            $task->parents()->attach($validated['parents']);
        }

        return Redirect::route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'event_id' => $task->event_id,
                'title' => $task->title,
                'content' => $task->content,
                'due_date' => $task->due_date->format('Y-m-d\TH:i'),
                'completed' => $task->completed,
                'deleted_at' => $task->deleted_at,
            ],
            'events' => Auth::user()->account->events()
                ->orderBy('title')
                ->get()
                ->map
                ->only('id', 'title'),
        ]);
    }

    public function update(Task $task)
    {
        $validated = Request::validate([
            'event_id' => ['required', 'exists:events,id'],
            'title' => ['required', 'max:100'],
            'content' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'completed' => ['boolean'],
        ]);

        $task->update($validated);

        return Redirect::back()->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', 'Task deleted.');
    }

    public function restore(Task $task)
    {
        $task->restore();

        return Redirect::back()->with('success', 'Task restored.');
    }
}
