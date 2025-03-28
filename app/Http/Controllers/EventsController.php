<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        return Inertia::render('Events/Index', [
            'filters' => Request::all('search', 'trashed'),
            'events' => Auth::user()->account->events()
                ->with('creator')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'type' => Event::getTypes()[$event->type],
                    'start_date' => $event->start_date->format('Y-m-d H:i'),
                    'duration' => $event->duration,
                    'location' => $event->location,
                    'online_link' => $event->online_link,
                    'created_by' => $event->creator->name,
                    'deleted_at' => $event->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        
        // If user is a parent, they cannot create events
        if ($user->isParent()) {
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'message' => __('Батьки не можуть створювати або змінювати події')
                ], 403);
            }
            
            return redirect()->route('events.index')
                ->with('error', __('Батьки не можуть створювати або змінювати події'));
        }
        
        $types = Event::getTypes();
        
        // If user is a student, only allow personal events
        if ($user->isStudent()) {
            $types = [Event::TYPE_PERSONAL => $types[Event::TYPE_PERSONAL]];
        }
        
        return Inertia::render('Events/Create', [
            'types' => $types,
            'students' => Auth::user()->account->students()
                ->orderByName()
                ->get()
                ->map
                ->only('id', 'name'),
            // Show teachers and parents only for non-students
            'teachers' => !$user->isStudent() ? Auth::user()->account->teachers()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name') : [],
            'parents' => !$user->isStudent() ? Auth::user()->account->parents()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name') : [],
        ]);
    }

    public function store()
    {
        $user = Auth::user();
        
        $validated = request()->validate([
            'title' => ['required', 'max:100'],
            'type' => ['required', 'in:' . implode(',', array_keys(Event::getTypes()))],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'content' => ['nullable', 'string'],
            'is_content_hidden' => ['boolean'],
            'location' => ['nullable', 'string', 'max:100'],
            'online_link' => ['nullable', 'url', 'max:200'],
            'tasks' => ['nullable', 'string'],
            'student_ids' => ['array'],
            'student_ids.*.id' => ['exists:contacts,id'],
            'teacher_ids' => ['array'],
            'teacher_ids.*.id' => ['exists:teachers,id'],
            'parent_ids' => ['array'],
            'parent_ids.*.id' => ['exists:parent_models,id'],
        ]);

        // For students, enforce that they can only create personal events and can't invite teachers or parents
        if ($user->isStudent()) {
            if ($validated['type'] !== Event::TYPE_PERSONAL) {
                return back()->with('error', __('Учні можуть створювати тільки особисті події'));
            }
            
            if (!empty($validated['teacher_ids'])) {
                return back()->with('error', __('Учні не можуть запрошувати вчителів на події'));
            }
            
            if (!empty($validated['parent_ids'])) {
                return back()->with('error', __('Учні не можуть запрошувати батьків на події'));
            }
        }

        $event = Auth::user()->account->events()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'duration' => $validated['duration'],
            'content' => $validated['content'],
            'is_content_hidden' => $validated['is_content_hidden'] ?? false,
            'location' => $validated['location'],
            'online_link' => $validated['online_link'],
            'tasks' => $validated['tasks'],
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['student_ids'])) {
            $event->students()->attach(collect($validated['student_ids'])->pluck('id'));
        }

        if (!empty($validated['teacher_ids'])) {
            $event->teachers()->attach(collect($validated['teacher_ids'])->pluck('id'));
        }

        if (!empty($validated['parent_ids'])) {
            $event->parents()->attach(collect($validated['parent_ids'])->pluck('id'));
        }

        return Redirect::route('events.index')->with('success', __('Event created.'));
    }

    public function edit(Event $event)
    {
        $user = Auth::user();
        $types = Event::getTypes();
        
        // If user is a student, only allow personal events
        if ($user->isStudent()) {
            $types = [Event::TYPE_PERSONAL => $types[Event::TYPE_PERSONAL]];
            
            // Students can only edit their own events
            if ($event->created_by !== $user->id) {
                return Redirect::back()->with('error', __('Учні можуть редагувати тільки власні події'));
            }
        }
        
        return Inertia::render('Events/Edit', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'type' => $event->type,
                'start_date' => $event->start_date,
                'duration' => $event->duration,
                'content' => $event->content,
                'is_content_hidden' => $event->is_content_hidden,
                'location' => $event->location,
                'online_link' => $event->online_link,
                'tasks' => $event->tasks,
                'deleted_at' => $event->deleted_at,
                'student_ids' => $event->students->map->only('id'),
                'teacher_ids' => $event->teachers->map->only('id'),
                'parent_ids' => $event->parents->map->only('id'),
                'created_by' => $event->created_by,
            ],
            'types' => $types,
            'students' => Auth::user()->account->students()
                ->orderByName()
                ->get()
                ->map
                ->only('id', 'name'),
            // Show teachers and parents only for non-students
            'teachers' => !$user->isStudent() ? Auth::user()->account->teachers()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name') : [],
            'parents' => !$user->isStudent() ? Auth::user()->account->parents()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name') : [],
        ]);
    }

    public function update(Event $event)
    {
        $user = Auth::user();
        
        $validated = Request::validate([
            'title' => ['required', 'max:100'],
            'type' => ['required', 'in:' . implode(',', array_keys(Event::getTypes()))],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'content' => ['nullable', 'string'],
            'is_content_hidden' => ['boolean'],
            'location' => ['nullable', 'string', 'max:100'],
            'online_link' => ['nullable', 'url', 'max:200'],
            'tasks' => ['nullable', 'string'],
            'student_ids' => ['array'],
            'student_ids.*.id' => ['exists:contacts,id'],
            'teacher_ids' => ['array'],
            'teacher_ids.*.id' => ['exists:teachers,id'],
            'parent_ids' => ['array'],
            'parent_ids.*.id' => ['exists:parent_models,id'],
        ]);

        // For students, enforce that they can only create personal events and can't invite teachers or parents
        if ($user->isStudent()) {
            if ($validated['type'] !== Event::TYPE_PERSONAL) {
                return back()->with('error', __('Учні можуть створювати тільки особисті події'));
            }
            
            if (!empty($validated['teacher_ids'])) {
                return back()->with('error', __('Учні не можуть запрошувати вчителів на події'));
            }
            
            if (!empty($validated['parent_ids'])) {
                return back()->with('error', __('Учні не можуть запрошувати батьків на події'));
            }
        }

        $event->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'duration' => $validated['duration'],
            'content' => $validated['content'],
            'is_content_hidden' => $validated['is_content_hidden'] ?? false,
            'location' => $validated['location'],
            'online_link' => $validated['online_link'],
            'tasks' => $validated['tasks'],
        ]);

        $event->students()->sync(collect($validated['student_ids'] ?? [])->pluck('id'));
        $event->teachers()->sync(collect($validated['teacher_ids'] ?? [])->pluck('id'));
        $event->parents()->sync(collect($validated['parent_ids'] ?? [])->pluck('id'));

        return Redirect::back()->with('success', __('Event updated.'));
    }

    public function destroy(Event $event)
    {
        $user = Auth::user();
        
        // Students can only delete their own events
        if ($user->isStudent() && $event->created_by !== $user->id) {
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'message' => __('Учні можуть видаляти тільки власні події')
                ], 403);
            }
            
            return Redirect::back()->with('error', __('Учні можуть видаляти тільки власні події'));
        }
        
        $event->delete();

        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'message' => __('Подію видалено')
            ]);
        }
        
        return Redirect::route('events.index')->with('success', __('Event deleted.'));
    }

    public function restore(Event $event)
    {
        $event->restore();

        return Redirect::back()->with('success', __('Event restored.'));
    }
} 