<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttachment;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $user = Auth::user();
        
        // Load all related data
        $event->load([
            'creator',
            'students',
            'teachers',
            'parents',
            'attachments'
        ]);
        
        // Check if user is participant or creator
        $isParticipant = false;
        $isCreator = $event->created_by === $user->id;
        $isAdmin = $user->isAdmin();
        
        if ($user->isStudent() && $event->students->contains('id', $user->contactId())) {
            $isParticipant = true;
        } elseif ($user->isTeacher() && $event->teachers->contains('id', $user->teacherId())) {
            $isParticipant = true;
        } elseif ($user->isParent() && $event->parents->contains('id', $user->parentId())) {
            $isParticipant = true;
        }
        
        // If user is not a participant, creator, or admin, redirect to calendar
        if (!$isParticipant && !$isCreator && !$isAdmin) {
            return redirect()->route('events.calendar')->with('error', 'Ви не є учасником цієї події.');
        }
        
        // Determine if the user can view content (if it's hidden)
        $canViewContent = !$event->is_content_hidden || 
                        $isCreator || 
                        $isAdmin || 
                        $event->start_date <= now();
                        
        // Determine user's participation status
        $participationStatus = 'pending';
        if ($user->isStudent() && $event->students->contains('id', $user->contactId())) {
            $participationStatus = $event->students->where('id', $user->contactId())->first()->pivot->status ?? 'pending';
        } elseif ($user->isTeacher() && $event->teachers->contains('id', $user->teacherId())) {
            $participationStatus = $event->teachers->where('id', $user->teacherId())->first()->pivot->status ?? 'pending';
        } elseif ($user->isParent() && $event->parents->contains('id', $user->parentId())) {
            $participationStatus = $event->parents->where('id', $user->parentId())->first()->pivot->status ?? 'pending';
        }
        
        return Inertia::render('Events/Show', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'type' => Event::getTypes()[$event->type],
                'start_date' => $event->start_date->format('Y-m-d H:i'),
                'duration' => $event->duration,
                'content' => $event->content,
                'is_content_hidden' => $event->is_content_hidden,
                'location' => $event->location,
                'online_link' => $event->online_link,
                'tasks' => $event->tasks,
                'creator' => [
                    'id' => $event->creator->id,
                    'name' => $event->creator->name,
                ],
                'students' => $event->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'participation_status' => $student->pivot->status ?? 'pending',
                    ];
                }),
                'teachers' => $event->teachers->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'participation_status' => $teacher->pivot->status ?? 'pending',
                    ];
                }),
                'parents' => $event->parents->map(function ($parent) {
                    return [
                        'id' => $parent->id,
                        'name' => $parent->name,
                        'participation_status' => $parent->pivot->status ?? 'pending',
                    ];
                }),
                'participation_status' => $participationStatus,
                'attachments' => $event->attachments->map(function ($attachment) use ($event) {
                    return [
                        'id' => $attachment->id,
                        'original_filename' => $attachment->original_filename,
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'download_url' => route('events.attachments.download', [$attachment->event_id, $attachment->id]),
                        'can_delete' => Auth::user()->id === $event->created_by || Auth::user()->isAdmin(),
                    ];
                }),
            ],
            'canViewContent' => $canViewContent,
            'isParticipant' => $isParticipant,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        
        // Для учнів зробимо спеціальний інтерфейс
        if ($user->isStudent()) {
            return Inertia::render('Events/Create', [
                'students' => Auth::user()->account->students()
                    ->orderByName()
                    ->get()
                    ->map->only('id', 'first_name', 'last_name', 'email', 'name'),
                'teachers' => [],  // Учні не можуть запрошувати вчителів
                'parents' => [],   // Учні не можуть запрошувати батьків
                'types' => Event::getTypes(),
                'eventTypes' => Event::getTypes(),
                'isStudent' => true,
            ]);
        }
        
        // Перевіряємо, чи є користувач батьком
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати події.');
        }
        
        return Inertia::render('Events/Create', [
            'students' => Auth::user()->account->students()
                ->orderByName()
                ->get()
                ->map->only('id', 'first_name', 'last_name', 'email', 'name'),
            'teachers' => Auth::user()->account->teachers()
                ->orderByName()
                ->get()
                ->map->only('id', 'first_name', 'last_name', 'email', 'name'),
            'parents' => Auth::user()->account->parents()
                ->orderByName()
                ->get()
                ->map->only('id', 'first_name', 'last_name', 'email', 'name'),
            'types' => Event::getTypes(),
            'eventTypes' => Event::getTypes(),
            'isStudent' => false,
        ]);
    }

    public function store()
    {
        $user = Auth::user();
        
        // Якщо це батько, забороняємо створення подій
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати події.');
        }
        
        $validated = request()->validate([
            'title' => ['required', 'max:100'],
            'type' => ['required', 'in:'.implode(',', array_keys(Event::getTypes()))],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'content' => ['nullable', 'string'],
            'location' => ['nullable', 'max:150'],
            'online_link' => ['nullable', 'url', 'max:255'],
            'is_content_hidden' => ['boolean'],
            'students' => ['array'],
            'students.*.id' => ['exists:contacts,id'],
            'teachers' => ['array'],
            'teachers.*.id' => ['exists:teachers,id'],
            'parents' => ['array'],
            'parents.*.id' => ['exists:parent_models,id'],
        ]);
        
        // Якщо користувач - учень, обмежуємо доступ до додавання вчителів та батьків
        if ($user->isStudent()) {
            // Обнуляємо списки вчителів та батьків, щоб учень не міг їх додати
            $validated['teachers'] = [];
            $validated['parents'] = [];
        }
        
        $event = Auth::user()->account->events()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'duration' => $validated['duration'],
            'content' => $validated['content'] ?? '',
            'location' => $validated['location'] ?? null,
            'online_link' => $validated['online_link'] ?? null,
            'is_content_hidden' => $validated['is_content_hidden'] ?? false,
            'created_by' => Auth::id(),
        ]);
        
        // Додаємо студентів
        if (!empty($validated['students'])) {
            $studentIds = collect($validated['students'])->pluck('id');
            $event->students()->attach($studentIds, ['status' => 'pending']);
        }
        
        // Додаємо вчителів (тільки якщо не учень)
        if (!empty($validated['teachers']) && !$user->isStudent()) {
            $teacherIds = collect($validated['teachers'])->pluck('id');
            $event->teachers()->attach($teacherIds, ['status' => 'pending']);
        }
        
        // Додаємо батьків (тільки якщо не учень)
        if (!empty($validated['parents']) && !$user->isStudent()) {
            $parentIds = collect($validated['parents'])->pluck('id');
            $event->parents()->attach($parentIds, ['status' => 'pending']);
        }
        
        // Якщо учень створив подію, автоматично додаємо його самого до події
        if ($user->isStudent()) {
            // Знаходимо студента за email користувача
            $student = Student::where('email', $user->email)->first();
            if ($student) {
                $event->students()->attach($student->id, ['status' => 'accepted']);
            }
        }
        
        return Redirect::route('events.index')->with('success', 'Подію створено.');
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
        
        // Load attachments
        $event->load('attachments');
        
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
                'attachments' => $event->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'original_filename' => $attachment->original_filename,
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'download_url' => route('events.attachments.download', [$attachment->event_id, $attachment->id]),
                    ];
                }),
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
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:102400'], // 100MB limit (in KB)
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

        // Upload attachments if any
        if (request()->hasFile('attachments')) {
            foreach (request()->file('attachments') as $file) {
                $path = $file->store('event-attachments');
                $event->attachments()->create([
                    'filename' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

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
        
        // Delete all attachments from storage
        foreach ($event->attachments as $attachment) {
            Storage::delete($attachment->filename);
        }
        
        $event->delete();

        if (request()->header('X-Inertia') === 'false') {
            return response()->json([
                'success' => true,
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

    /**
     * Download an attachment file.
     */
    public function downloadAttachment(Event $event, EventAttachment $attachment)
    {
        // Check if the attachment belongs to the event
        if ($attachment->event_id !== $event->id) {
            abort(404);
        }

        // Check if user can access this event
        $user = Auth::user();
        
        // Basic access check (can be extended based on your requirements)
        if ($user->id !== $event->created_by && 
            !$event->students->contains('id', $user->contactId()) && 
            !$event->teachers->contains('id', $user->teacherId()) &&
            !$event->parents->contains('id', $user->parentId())) {
            abort(403);
        }

        return Storage::download(
            $attachment->filename,
            $attachment->original_filename
        );
    }

    /**
     * Remove an attachment from an event.
     */
    public function removeAttachment(Event $event, EventAttachment $attachment)
    {
        // Check if the attachment belongs to the event
        if ($attachment->event_id !== $event->id) {
            abort(404);
        }

        // Check if user can delete this attachment
        $user = Auth::user();
        
        // Only the event creator or admins can delete attachments
        if ($user->id !== $event->created_by && !$user->isAdmin()) {
            return Redirect::back()->with('error', __('You cannot delete attachments from this event.'));
        }

        // Delete the file from storage
        Storage::delete($attachment->filename);
        
        // Delete the record
        $attachment->delete();

        return Redirect::back()->with('success', __('Attachment removed.'));
    }

    /**
     * API endpoint to get event details including attachments.
     */
    public function apiGetEvent(Event $event)
    {
        $user = Auth::user();
        
        // Load all related data including attachments
        $event->load([
            'creator',
            'students',
            'teachers',
            'parents',
            'attachments'
        ]);
        
        // Check if user is participant or creator
        $isParticipant = false;
        $isCreator = $event->created_by === $user->id;
        $isAdmin = $user->isAdmin();
        
        if ($user->isStudent() && $user->contactId() && $event->students->contains('id', $user->contactId())) {
            $isParticipant = true;
        } elseif ($user->isTeacher() && $user->teacherId() && $event->teachers->contains('id', $user->teacherId())) {
            $isParticipant = true;
        } elseif ($user->isParent() && $user->parentId() && $event->parents->contains('id', $user->parentId())) {
            $isParticipant = true;
        }
        
        // If user is not a participant, creator, or admin, return an error
        if (!$isParticipant && !$isCreator && !$isAdmin) {
            return response()->json([
                'error' => 'Ви не є учасником цієї події.'
            ], 403);
        }
        
        // Determine if the user can view content (if it's hidden)
        $canViewContent = !$event->is_content_hidden || 
                        $event->created_by === $user->id || 
                        $user->isAdmin() || 
                        $event->start_date <= now();
                        
        // Return JSON response with event data
        return response()->json([
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'type' => $event->type,
                'type_label' => Event::getTypes()[$event->type] ?? $event->type,
                'start_date' => $event->start_date->format('Y-m-d H:i:s'),
                'duration' => $event->duration,
                'content' => $canViewContent ? $event->content : null,
                'is_content_hidden' => $event->is_content_hidden,
                'location' => $event->location,
                'online_link' => $event->online_link,
                'tasks' => $canViewContent ? $event->tasks : null,
                'creator' => [
                    'id' => $event->creator->id,
                    'name' => $event->creator->name,
                ],
                'created_by' => $event->creator->name,
                'created_by_id' => $event->created_by,
                'attachments' => $event->attachments->map(function ($attachment) use ($event, $user) {
                    return [
                        'id' => $attachment->id,
                        'original_filename' => $attachment->original_filename,
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                        'download_url' => route('events.attachments.download', [$attachment->event_id, $attachment->id]),
                        'can_delete' => $user->id === $event->created_by || $user->isAdmin(),
                    ];
                }),
            ],
            'canViewContent' => $canViewContent,
            'isParticipant' => $isParticipant,
        ]);
    }

    /**
     * Update the participation status for the current user for this event.
     *
     * @param int $eventId
     * @return \Illuminate\Http\Response
     */
    public function updateParticipation($eventId)
    {
        // Find the event by ID
        $event = Event::findOrFail($eventId);
        
        $user = Auth::user();
        $status = request()->input('status');

        // Validate the status
        request()->validate([
            'status' => ['required', 'in:accepted,declined,pending'],
        ]);

        $contactId = $user->contactId();
        $teacherId = $user->teacherId();
        $parentId = $user->parentId();

        // Update the participation status based on user role
        $updated = false;
        
        if ($user->isStudent() && $contactId && $event->students->contains('id', $contactId)) {
            $event->students()->updateExistingPivot($contactId, ['status' => $status]);
            $updated = true;
        } elseif ($user->isTeacher() && $teacherId && $event->teachers->contains('id', $teacherId)) {
            $event->teachers()->updateExistingPivot($teacherId, ['status' => $status]);
            $updated = true;
        } elseif ($user->isParent() && $parentId && $event->parents->contains('id', $parentId)) {
            $event->parents()->updateExistingPivot($parentId, ['status' => $status]);
            $updated = true;
        }

        if (!$updated) {
            if (request()->header('X-Inertia')) {
                // For Inertia requests, redirect to calendar with a flash message
                return redirect()->route('events.calendar')->with('error', 'Ви не є учасником цієї події або не маєте дозволу змінювати статус участі.');
            } elseif (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Ви не є учасником цієї події або не маєте дозволу змінювати статус участі.'
                ], 403);
            }
            
            return redirect()->back()->with('error', 'Ви не є учасником цієї події або не маєте дозволу змінювати статус участі.');
        }
        
        // For Inertia requests, redirect back with a flash message
        if (request()->header('X-Inertia')) {
            return back()->with('success', 'Ваш статус участі оновлено.');
        } elseif (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ваш статус участі оновлено.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Ваш статус участі оновлено.');
    }

    /**
     * Check if current user has permissions to create events
     * Used by the calendar UI to determine if the create button should work
     */
    public function checkCreatePermissions()
    {
        $user = Auth::user();
        
        // Учні можуть створювати події для себе та інших учнів
        if ($user->isStudent()) {
            return response()->json([
                'success' => true,
                'can_create' => true,
                'is_student' => true,
                'message' => __('Учні можуть створювати події тільки для інших учнів')
            ]);
        }
        
        // Батьки не можуть створювати події
        if ($user->isParent()) {
            return response()->json([
                'message' => __('Батьки не можуть створювати або змінювати події')
            ], 403);
        }
        
        // Всі інші користувачі (вчителі, адміни) можуть створювати події
        return response()->json([
            'success' => true,
            'can_create' => true,
            'is_student' => false
        ]);
    }
    
    /**
     * Display calendar view for events
     */
    public function calendar()
    {
        $user = Auth::user();
        
        // Get current date for default view
        $currentDate = now()->format('Y-m-d');
        
        // Get events for the calendar
        $query = Event::query();
        
        // If user is admin, show all events
        if ($user->isAdmin()) {
            // Admin can see all events
        } else {
            // Non-admin users can only see events they're related to
            $query->where(function($subquery) use ($user) {
                // Events created by the user
                $subquery->where('created_by', $user->id);
                
                // Events where the user is a participant
                if ($user->isStudent() && $user->contactId()) {
                    $subquery->orWhereHas('students', function($q) use ($user) {
                        $q->where('id', $user->contactId());
                    });
                }
                
                if ($user->isTeacher() && $user->teacherId()) {
                    $subquery->orWhereHas('teachers', function($q) use ($user) {
                        $q->where('id', $user->teacherId());
                    });
                }
                
                if ($user->isParent() && $user->parentId()) {
                    $subquery->orWhereHas('parents', function($q) use ($user) {
                        $q->where('id', $user->parentId());
                    });
                }
            });
        }
        
        // Time range filter (always apply this regardless of user role)
        $query->where('start_date', '>=', now()->subDays(30))
              ->where('start_date', '<=', now()->addDays(60));
        
        $events = $query->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d H:i:s'),
                    'end' => $event->start_date->addMinutes($event->duration)->format('Y-m-d H:i:s'),
                    'type' => $event->type,
                    'location' => $event->location,
                    'online_link' => $event->online_link,
                    'description' => $event->description,
                ];
            });
        
        return Inertia::render('Calendar/Index', [
            'currentDate' => $currentDate,
            'language' => session('language', 'uk'),
            'events' => $events
        ]);
    }
} 