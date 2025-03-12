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
        return Inertia::render('Events/Create', [
            'types' => Event::getTypes(),
            'students' => Auth::user()->account->contacts()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'teachers' => Auth::user()->account->teachers()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'parents' => Auth::user()->account->parents()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
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

        return Redirect::route('events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event)
    {
        return Inertia::render('Events/Edit', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'type' => $event->type,
                'start_date' => $event->start_date->format('Y-m-d\TH:i'),
                'duration' => $event->duration,
                'content' => $event->content,
                'is_content_hidden' => $event->is_content_hidden,
                'location' => $event->location,
                'online_link' => $event->online_link,
                'tasks' => $event->tasks,
                'deleted_at' => $event->deleted_at,
                'student_ids' => $event->students->map(fn($student) => ['id' => $student->id, 'name' => $student->name]),
                'teacher_ids' => $event->teachers->map(fn($teacher) => ['id' => $teacher->id, 'name' => $teacher->name]),
                'parent_ids' => $event->parents->map(fn($parent) => ['id' => $parent->id, 'name' => $parent->name]),
            ],
            'types' => Event::getTypes(),
            'students' => Auth::user()->account->contacts()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'teachers' => Auth::user()->account->teachers()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
            'parents' => Auth::user()->account->parents()
                ->orderBy('last_name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Event $event)
    {
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

        return Redirect::back()->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return Redirect::back()->with('success', 'Event deleted.');
    }

    public function restore(Event $event)
    {
        $event->restore();

        return Redirect::back()->with('success', 'Event restored.');
    }
} 