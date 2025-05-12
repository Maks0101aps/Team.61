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
                    'event' => $task->event ? $task->event->title : null,
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
            return Redirect::route('dashboard')->with('error', __('messages.students_cannot_create_tasks'));
        }
        
        // Check if user is a parent
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_create_tasks'));
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
                'language' => session('language', 'uk'),
        ]);
    }

    public function store()
    {
        $user = Auth::user();
        
        // Check if user is a student
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', __('messages.students_cannot_create_tasks'));
        }
        
        // Check if user is a parent
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_create_tasks'));
        }
        
        $validated = Request::validate([
            'event_id' => ['nullable', 'exists:events,id'],
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
            'event_id' => $validated['event_id'] ?? null,
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
        $user = Auth::user();
        
        // Parents cannot edit tasks
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_edit_tasks'));
        }
        
        // Students can only edit their own tasks
        if ($user->isStudent()) {
            $contactId = $user->contactId();
            
            // Check if task belongs to the student (was created by them or assigned to them)
            $isOwnTask = $task->created_by === $user->id;
            $isAssignedToStudent = $contactId && $task->students->contains('id', $contactId);
            
            if (!$isOwnTask && !$isAssignedToStudent) {
                return Redirect::route('dashboard')->with('error', __('messages.students_can_only_edit_own_tasks'));
            }
        }
        
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
            'language' => session('language', 'uk'),
        ]);
    }

    public function update(Task $task)
    {
        $user = Auth::user();
        
        // Parents cannot update tasks
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_edit_tasks'));
        }
        
        // Students can only update their own tasks or tasks assigned to them
        if ($user->isStudent()) {
            $contactId = $user->contactId();
            
            // Check if task belongs to the student (was created by them or assigned to them)
            $isOwnTask = $task->created_by === $user->id;
            $isAssignedToStudent = $contactId && $task->students->contains('id', $contactId);
            
            if (!$isOwnTask && !$isAssignedToStudent) {
                return Redirect::route('dashboard')->with('error', __('messages.students_can_only_edit_own_tasks'));
            }
            
            // Students can only update the completion status
            $validated = Request::validate([
                'completed' => ['boolean'],
            ]);
            
            $task->update([
                'completed' => $validated['completed'],
            ]);
            
            return Redirect::back()->with('success', __('Task updated successfully.'));
        }
        
        // Teachers and admins can update all fields
        $validated = Request::validate([
            'event_id' => ['nullable', 'exists:events,id'],
            'title' => ['sometimes', 'required', 'max:100'],
            'content' => ['nullable', 'string'],
            'due_date' => ['sometimes', 'required', 'date'],
            'completed' => ['boolean'],
            'students' => ['sometimes', 'array'],
            'students.*.id' => ['exists:contacts,id'],
            'teachers' => ['sometimes', 'array'],
            'teachers.*.id' => ['exists:teachers,id'],
            'parents' => ['sometimes', 'array'],
            'parents.*.id' => ['exists:parent_models,id'],
        ]);

        $task->update([
            'event_id' => $validated['event_id'] ?? null,
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
        $user = Auth::user();
        
        // Parents cannot delete tasks
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_delete_tasks'));
        }
        
        // Students can only delete their own tasks
        if ($user->isStudent()) {
            if ($task->created_by !== $user->id) {
                return Redirect::route('dashboard')->with('error', __('messages.students_can_only_delete_own_tasks'));
            }
        }
        
        $task->delete();

        return Redirect::back()->with('success', __('Task deleted successfully.'));
    }

    public function restore(Task $task)
    {
        $user = Auth::user();
        
        // Parents cannot restore tasks
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', __('messages.parents_cannot_restore_tasks'));
        }
        
        // Students can only restore their own tasks
        if ($user->isStudent()) {
            if ($task->created_by !== $user->id) {
                return Redirect::route('dashboard')->with('error', __('messages.students_can_only_restore_own_tasks'));
            }
        }
        
        $task->restore();

        return Redirect::back()->with('success', __('Task restored successfully.'));
    }
}
