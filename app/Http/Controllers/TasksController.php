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
        $user = Auth::user();
        
        // Check if user is a student
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', __('Students cannot create tasks.'));
        }
        
        // Check if user is a parent
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('Parents cannot create tasks.'));
        }
        
        return Inertia::render('Tasks/Create', [
            'events' => Auth::user()->account->events()
                ->orderBy('title')
                ->get()
                ->map
                ->only('id', 'title'),
            'students' => Auth::user()->account->students()
                ->orderByName()
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
        $user = Auth::user();
        
        // Check if user is a student
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', __('Students cannot create tasks.'));
        }
        
        // Check if user is a parent
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('Parents cannot create tasks.'));
        }
        
        $validated = Request::validate([
            'event_id' => ['required', 'exists:events,id'],
            'title' => ['required', 'max:100'],
            'content' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'students' => ['array'],
            'students.*.id' => ['exists:contacts,id'],
            'teachers' => ['array'],
            'teachers.*.id' => ['exists:teachers,id'],
            'parents' => ['array'],
            'parents.*.id' => ['exists:parent_models,id'],
        ]);

        $task = Auth::user()->account->tasks()->create([
            'event_id' => $validated['event_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'due_date' => $validated['due_date'],
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['students'])) {
            $task->students()->attach(collect($validated['students'])->pluck('id'));
        }

        if (!empty($validated['teachers'])) {
            $task->teachers()->attach(collect($validated['teachers'])->pluck('id'));
        }

        if (!empty($validated['parents'])) {
            $task->parents()->attach(collect($validated['parents'])->pluck('id'));
        }

        return Redirect::route('tasks.index')->with('success', __('Task created successfully.'));
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'content' => $task->content,
                'due_date' => $task->due_date,
                'completed' => $task->completed,
                'event_id' => $task->event_id,
                'students' => $task->students->map->only('id', 'name'),
                'teachers' => $task->teachers->map->only('id', 'name'),
                'parents' => $task->parents->map->only('id', 'name'),
                'deleted_at' => $task->deleted_at,
            ],
            'events' => Auth::user()->account->events()
                ->orderBy('title')
                ->get()
                ->map
                ->only('id', 'title'),
            'students' => Auth::user()->account->students()
                ->orderByName()
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

    public function update(Task $task)
    {
        $validated = Request::validate([
            'event_id' => ['required', 'exists:events,id'],
            'title' => ['required', 'max:100'],
            'content' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'completed' => ['boolean'],
            'students' => ['array'],
            'students.*.id' => ['exists:contacts,id'],
            'teachers' => ['array'],
            'teachers.*.id' => ['exists:teachers,id'],
            'parents' => ['array'],
            'parents.*.id' => ['exists:parent_models,id'],
        ]);

        $task->update([
            'event_id' => $validated['event_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'due_date' => $validated['due_date'],
            'completed' => $validated['completed'],
        ]);

        $task->students()->sync(collect($validated['students'] ?? [])->pluck('id'));
        $task->teachers()->sync(collect($validated['teachers'] ?? [])->pluck('id'));
        $task->parents()->sync(collect($validated['parents'] ?? [])->pluck('id'));

        return Redirect::back()->with('success', __('Task updated successfully.'));
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', __('Task deleted successfully.'));
    }

    public function restore(Task $task)
    {
        $task->restore();

        return Redirect::back()->with('success', __('Task restored successfully.'));
    }
}
