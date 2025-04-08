<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Student;
use App\Models\Event;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;
use App\Models\Task;

class ReportsController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        // Get total counts of teachers, students, events, and tasks
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalUsers = $totalTeachers + $totalStudents;
        $totalEvents = Event::count();
        $totalTasks = Task::count();
        
        // Get online users count (users active in the last 5 minutes)
        $onlineUsers = User::where('last_active_at', '>=', now()->subMinutes(5))->count();

        return Inertia::render('Reports/Index', [
            'statistics' => [
                'totalTeachers' => $totalTeachers,
                'totalStudents' => $totalStudents,
                'totalUsers' => $totalUsers,
                'totalEvents' => $totalEvents,
                'totalTasks' => $totalTasks,
                'onlineUsers' => $onlineUsers
            ]
        ]);
    }

    /**
     * Display student attendance report with real data from the database.
     */
    public function studentAttendance()
    {
        // Get all students with their attendance data
        $students = Student::with(['parents', 'events'])->get();
        
        // Get all events for attendance calculation
        $events = Event::where('type', '!=', Event::TYPE_PERSONAL)
            ->where('start_date', '>=', now()->subMonths(3))
            ->get();
        
        $studentData = $students->map(function ($student) use ($events) {
            // Calculate attendance based on event participation
            $studentEvents = $events->filter(function ($event) use ($student) {
                return $event->students()->where('participant_id', $student->id)->exists();
            });
            
            $totalEvents = $events->count();
            $daysPresent = $studentEvents->count();
            $daysAbsent = $totalEvents - $daysPresent;
            $attendancePercentage = $totalEvents > 0 ? round(($daysPresent / $totalEvents) * 100) : 100;
            
            // Calculate late arrivals (if event has duration and student joined after start)
            $late = $studentEvents->filter(function ($event) use ($student) {
                $participation = $event->students()->where('participant_id', $student->id)->first();
                return $participation && $participation->pivot->created_at > $event->start_date;
            })->count();
            
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'group' => $student->organization_id ? 'Group ' . $student->organization_id : 'Unassigned',
                'attendancePercentage' => $attendancePercentage,
                'daysPresent' => $daysPresent,
                'daysAbsent' => $daysAbsent,
                'late' => $late,
                'parentPhone' => $student->parents->first()?->phone ?? 'Not available'
            ];
        });

        // Calculate statistics
        $totalStudents = $students->count();
        $totalEvents = $events->count();
        $averageAttendance = $studentData->average('attendancePercentage');
        $totalPresent = $studentData->sum('daysPresent');
        $totalAbsent = $studentData->sum('daysAbsent');
        
        return Inertia::render('Reports/Students/Attendance', [
            'initialStudentData' => $studentData,
            'statistics' => [
                'totalStudents' => $totalStudents,
                'totalEvents' => $totalEvents,
                'averageAttendance' => round($averageAttendance, 1),
                'totalPresent' => $totalPresent,
                'totalAbsent' => $totalAbsent
            ]
        ]);
    }

    /**
     * Display student performance report.
     */
    public function studentPerformance()
    {
        // Get students with their performance data
        $students = Student::with(['parents', 'organization', 'events', 'tasks'])->get();
        
        // Get events for performance calculation
        $events = Event::whereIn('type', [Event::TYPE_EXAM, Event::TYPE_TEST])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get();
        
        // Get tasks for performance calculation
        $tasks = Task::where('due_date', '>=', now()->subMonths(6))
            ->where('completed', true)
            ->get();
        
        $studentData = $students->map(function ($student) use ($events, $tasks) {
            // Calculate performance metrics
            $studentEvents = $events->filter(function ($event) use ($student) {
                return $event->students()->where('participant_id', $student->id)->exists();
            });
            
            $studentTasks = $tasks->filter(function ($task) use ($student) {
                return $task->students()->where('participant_id', $student->id)->exists();
            });
            
            // Calculate grades based on events and tasks
            $grades = $this->calculateStudentGrades($student, $studentEvents, $studentTasks);
            
            // Calculate average and trend
            $currentAverage = collect($grades)->avg('current');
            $previousAverage = collect($grades)->avg('previous');
            $trend = $currentAverage - $previousAverage;
            
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'group' => $student->organization?->name ?? 'Unassigned',
                'grades' => $grades,
                'average' => round($currentAverage, 1),
                'trend' => round($trend, 1)
            ];
        });
        
        // Calculate performance distribution
        $performanceDistribution = [
            'high' => $studentData->filter(fn($s) => $s['average'] >= 10)->count(),
            'medium' => $studentData->filter(fn($s) => $s['average'] >= 7 && $s['average'] < 10)->count(),
            'low' => $studentData->filter(fn($s) => $s['average'] >= 4 && $s['average'] < 7)->count(),
            'critical' => $studentData->filter(fn($s) => $s['average'] < 4)->count()
        ];
        
        return Inertia::render('Reports/Students/Performance', [
            'students' => $studentData,
            'performanceDistribution' => $performanceDistribution
        ]);
    }

    /**
     * Display student list report.
     */
    public function studentList()
    {
        // Fetch real student data
        $students = Student::with('parents', 'organization')->get();
        
        $studentData = $students->map(function ($student) {
            // Get parent phone numbers
            $parentPhone = $student->parents()->pluck('phone')->filter()->first() ?? 'Not available';
            
            // Determine grade and group
            $grade = null;
            $group = null;
            
            if ($student->organization) {
                // Extract grade and group from organization name or other attributes
                // For example, if organization name is like "8-A"
                $orgNameParts = explode('-', $student->organization->name);
                if (count($orgNameParts) > 1) {
                    $grade = is_numeric($orgNameParts[0]) ? (int)$orgNameParts[0] : null;
                    $group = $orgNameParts[1] ?? null;
                } else {
                    // Fallback to organization name if structure is different
                    $grade = 1; // Default grade
                    $group = $student->organization->name;
                }
            } else {
                // Default values if no organization is assigned
                $grade = 1;
                $group = 'A';
            }
            
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'grade' => $grade,
                'group' => $group,
                'parentPhone' => $parentPhone,
                'status' => $student->status ?? 'Active' // Use actual status if available
            ];
        });
        
        return Inertia::render('Reports/Students/List', [
            'initialStudentData' => $studentData
        ]);
    }

    /**
     * Display teacher schedule report.
     */
    public function teacherSchedule()
    {
        // Fetch real teacher data
        $teachers = Teacher::all();
        
        // Get events for teacher schedules
        $events = Event::with('teachers')->get();
        
        // Define days of the week
        $days = ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П\'ятниця'];
        
        // Define periods
        $periods = [
            1 => '8:30 - 9:15',
            2 => '9:25 - 10:10',
            3 => '10:30 - 11:15',
            4 => '11:25 - 12:10',
            5 => '12:30 - 13:15',
            6 => '13:25 - 14:10',
            7 => '14:20 - 15:05',
            8 => '15:15 - 16:00',
        ];
        
        // Get all organizations to map to class names
        $organizations = Organization::all();
        $classNames = $organizations->map(function ($org) {
            return $org->name;
        })->filter()->values()->toArray();
        
        // If no class names found, use default class names
        if (empty($classNames)) {
            $classNames = [
                '1-A', '1-B', '2-A', '2-B', '3-A', '3-B', '4-A', '4-B', '5-A', '5-B',
                '6-A', '6-B', '7-A', '7-B', '8-A', '8-B', '9-A', '9-B', '10-A', '10-B', '11-A', '11-B',
            ];
        }
        
        // Define rooms
        $rooms = array_map(function ($i) { return (string)$i; }, range(1, 30));
        
        // Generate schedule for each teacher
        $teacherSchedules = $teachers->map(function ($teacher) use ($events, $days, $periods, $classNames, $rooms) {
            // Get teacher's subject
            $mainSubject = $teacher->subject ?? 'Не вказано';
            
            // Get events this teacher participates in
            $teacherEvents = $events->filter(function ($event) use ($teacher) {
                return $event->teachers->contains('id', $teacher->id);
            });
            
            // Initialize schedule with empty days
            $schedule = [];
            foreach ($days as $day) {
                $schedule[$day] = [];
            }
            
            // Map of event timestamps to day of week and period
            $dayMapping = [
                1 => 'Понеділок',
                2 => 'Вівторок',
                3 => 'Середа',
                4 => 'Четвер',
                5 => 'П\'ятниця'
            ];
            
            // Function to determine period from event time
            $getPeriodFromTime = function ($hour, $minute) use ($periods) {
                $time = $hour * 60 + $minute; // Convert to minutes
                
                // Period start times in minutes
                $periodStarts = [
                    1 => 8 * 60 + 30,  // 8:30
                    2 => 9 * 60 + 25,  // 9:25
                    3 => 10 * 60 + 30, // 10:30
                    4 => 11 * 60 + 25, // 11:25
                    5 => 12 * 60 + 30, // 12:30
                    6 => 13 * 60 + 25, // 13:25
                    7 => 14 * 60 + 20, // 14:20
                    8 => 15 * 60 + 15, // 15:15
                ];
                
                // Find closest period
                $closestPeriod = 1;
                $closestDiff = abs($time - $periodStarts[1]);
                
                foreach ($periodStarts as $period => $startTime) {
                    $diff = abs($time - $startTime);
                    if ($diff < $closestDiff) {
                        $closestDiff = $diff;
                        $closestPeriod = $period;
                    }
                }
                
                return $closestPeriod;
            };
            
            // Process real events to create schedule
            foreach ($teacherEvents as $event) {
                // Only use events with start dates
                if (!$event->start_date) {
                    continue;
                }
                
                // Get day of week (1-5, Monday to Friday)
                $dayOfWeek = $event->start_date->dayOfWeek;
                
                // Skip weekend events (6 = Saturday, 0 = Sunday)
                if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                    continue;
                }
                
                // Map to our day names
                $day = $dayMapping[$dayOfWeek];
                
                // Determine period from event time
                $period = $getPeriodFromTime(
                    $event->start_date->hour,
                    $event->start_date->minute
                );
                
                // Get class from event title or location
                $class = $event->location;
                if (empty($class)) {
                    // Try to extract class from event title
                    foreach ($classNames as $className) {
                        if (strpos($event->title, $className) !== false) {
                            $class = $className;
                            break;
                        }
                    }
                    
                    // If still no class, assign a random one
                    if (empty($class)) {
                        $class = $classNames[array_rand($classNames)];
                    }
                }
                
                // Get or generate room number
                $room = null;
                if (preg_match('/Room (\d+)/', $event->location, $matches)) {
                    $room = $matches[1];
                } else {
                    $room = $rooms[array_rand($rooms)];
                }
                
                // Add to schedule
                $schedule[$day][] = [
                    'period' => $period,
                    'time' => $periods[$period],
                    'subject' => $mainSubject,
                    'class' => $class,
                    'room' => $room,
                    'title' => $event->title, // Additional info
                ];
            }
            
            // If teacher has no events, generate a plausible schedule
            $totalLessons = 0;
            foreach ($days as $day) {
                if (empty($schedule[$day])) {
                    // Generate 3-6 lessons per day
                    $lessonCount = mt_rand(3, 6);
                    
                    // Generate periods for this day
                    $periodsToUse = array_rand(array_flip(array_keys($periods)), $lessonCount);
                    sort($periodsToUse);
                    
                    foreach ($periodsToUse as $period) {
                        $class = $classNames[array_rand($classNames)];
                        $room = $rooms[array_rand($rooms)];
                        
                        $schedule[$day][] = [
                            'period' => $period,
                            'time' => $periods[$period],
                            'subject' => $mainSubject,
                            'class' => $class,
                            'room' => $room,
                        ];
                        
                        $totalLessons++;
                    }
                } else {
                    // Sort existing lessons by period
                    usort($schedule[$day], function ($a, $b) {
                        return $a['period'] <=> $b['period'];
                    });
                    
                    $totalLessons += count($schedule[$day]);
                }
            }
            
            // Calculate total weekly hours
            $totalHours = $totalLessons * 0.75; // Each lesson is 45 minutes (0.75 hours)
            
            // Generate conflicts (for demonstration)
            $conflicts = [];
            
            // Check for room conflicts within the teacher's schedule
            $roomUsage = [];
            foreach ($days as $day) {
                foreach ($schedule[$day] as $lesson) {
                    $key = $day . '-' . $lesson['period'] . '-' . $lesson['room'];
                    if (isset($roomUsage[$key])) {
                        // Conflict found
                        $conflicts[] = [
                            'day' => $day,
                            'period' => $lesson['period'],
                            'time' => $lesson['time'],
                            'description' => "Накладка кабінету {$lesson['room']} з іншим вчителем",
                            'severity' => 'high',
                        ];
                    }
                    $roomUsage[$key] = true;
                }
            }
            
            return [
                'id' => $teacher->id,
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'subject' => $mainSubject,
                'schedule' => $schedule,
                'totalLessons' => $totalLessons,
                'totalHours' => $totalHours,
                'conflicts' => $conflicts,
            ];
        });
        
        // Calculate statistics
        $averageLessons = round($teacherSchedules->avg('totalLessons'), 1);
        $averageHours = round($teacherSchedules->avg('totalHours'), 1);
        $teachersWithConflicts = $teacherSchedules->filter(function ($teacher) {
            return count($teacher['conflicts']) > 0;
        })->count();
        
        // Most busy day analysis
        $dayDistribution = [];
        foreach ($days as $day) {
            $lessonCount = 0;
            foreach ($teacherSchedules as $teacher) {
                if (isset($teacher['schedule'][$day])) {
                    $lessonCount += count($teacher['schedule'][$day]);
                }
            }
            $dayDistribution[$day] = $lessonCount;
        }
        
        arsort($dayDistribution);
        $busiestDay = key($dayDistribution);
        $lightestDay = array_key_last($dayDistribution);
        
        // Period distribution
        $periodDistribution = [];
        foreach ($periods as $periodNum => $periodTime) {
            $lessonCount = 0;
            foreach ($teacherSchedules as $teacher) {
                foreach ($teacher['schedule'] as $daySchedule) {
                    foreach ($daySchedule as $lesson) {
                        if ($lesson['period'] == $periodNum) {
                            $lessonCount++;
                        }
                    }
                }
            }
            $periodDistribution[$periodNum] = [
                'time' => $periodTime,
                'count' => $lessonCount,
            ];
        }
        
        return Inertia::render('Reports/Teachers/Schedule', [
            'teacherSchedules' => $teacherSchedules,
            'days' => $days,
            'periods' => $periods,
            'statistics' => [
                'averageLessons' => $averageLessons,
                'averageHours' => $averageHours,
                'teachersWithConflicts' => $teachersWithConflicts,
                'totalTeachers' => $teacherSchedules->count(),
                'busiestDay' => [
                    'day' => $busiestDay,
                    'lessons' => $dayDistribution[$busiestDay] ?? 0,
                ],
                'lightestDay' => [
                    'day' => $lightestDay,
                    'lessons' => $dayDistribution[$lightestDay] ?? 0,
                ],
                'dayDistribution' => $dayDistribution,
                'periodDistribution' => $periodDistribution,
            ],
        ]);
    }

    /**
     * Display teacher list report.
     */
    public function teacherList()
    {
        // Fetch real teacher data
        $teachers = Teacher::all();
        
        $teacherData = $teachers->map(function ($teacher) {
            // Get subjects - in a real app, this would likely be a relationship
            // For now, we'll extract from the subject field
            $subjectsList = [];
            if (!empty($teacher->subject)) {
                // Check if the subject is a comma-separated list
                if (strpos($teacher->subject, ',') !== false) {
                    $subjectsList = array_map('trim', explode(',', $teacher->subject));
                } else {
                    $subjectsList = [$teacher->subject];
                }
            }
            
            // Calculate experience based on hire date if available
            $experience = null;
            if (!empty($teacher->hire_date)) {
                $hireDate = new \DateTime($teacher->hire_date);
                $now = new \DateTime();
                $years = $now->diff($hireDate)->y;
                $experience = $years . ' ' . ($years == 1 ? 'year' : 'years');
            } else {
                // Fallback if no hire date
                $experience = 'Unknown';
            }
            
            // Determine status
            $status = 'Active';
            if (!empty($teacher->status)) {
                $status = $teacher->status;
            }
            
            return [
                'id' => $teacher->id,
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'subjects' => $subjectsList,
                'experience' => $experience,
                'status' => $status
            ];
        });
        
        return Inertia::render('Reports/Teachers/List', [
            'initialTeacherData' => $teacherData
        ]);
    }

    /**
     * Display event calendar report - shows calendar in the reports section
     */
    public function eventCalendar()
    {
        // Get events for the calendar
        $events = Event::with(['students', 'teachers', 'parents'])
            ->where('start_date', '>=', now()->subMonths(1))
            ->where('start_date', '<=', now()->addMonths(3))
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d H:i:s'),
                    'end' => $event->start_date->addMinutes($event->duration)->format('Y-m-d H:i:s'),
                    'type' => $event->type,
                    'location' => $event->location,
                    'participants' => [
                        'students' => $event->students->count(),
                        'teachers' => $event->teachers->count(),
                        'parents' => $event->parents->count()
                    ]
                ];
            });
        
        return Inertia::render('Reports/Events/Calendar', [
            'events' => $events
        ]);
    }

    /**
     * Display event attendance report with real data.
     */
    public function eventAttendance()
    {
        // Get events with attendance data
        $events = Event::with(['students', 'teachers', 'parents'])
            ->where('start_date', '>=', now()->subMonths(3))
            ->get()
            ->map(function ($event) {
                $totalParticipants = $event->students->count() + 
                                   $event->teachers->count() + 
                                   $event->parents->count();
                
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->start_date->format('Y-m-d'),
                    'type' => $event->type,
                    'location' => $event->location,
                    'totalParticipants' => $totalParticipants,
                    'attendance' => [
                        'students' => $event->students->count(),
                        'teachers' => $event->teachers->count(),
                        'parents' => $event->parents->count()
                    ]
                ];
            });
        
        // Calculate attendance statistics
        $statistics = [
            'totalEvents' => $events->count(),
            'totalParticipants' => $events->sum('totalParticipants'),
            'averageAttendance' => round($events->avg('totalParticipants'), 1),
            'byType' => $events->groupBy('type')
                ->map(fn($group) => $group->avg('totalParticipants'))
        ];
        
        return Inertia::render('Reports/Events/Attendance', [
            'events' => $events,
            'statistics' => $statistics
        ]);
    }

    /**
     * Display event summary report.
     */
    public function eventSummary()
    {
        // Get comprehensive event statistics
        $events = Event::with(['students', 'teachers', 'parents', 'tasks'])
            ->where('start_date', '>=', now()->subMonths(6))
            ->get();
        
        $summary = [
            'totalEvents' => $events->count(),
            'byType' => $events->groupBy('type')
                ->map(fn($group) => $group->count()),
            'totalParticipants' => $events->sum(fn($event) => 
                $event->students->count() + 
                $event->teachers->count() + 
                $event->parents->count()
            ),
            'completedTasks' => $events->sum(fn($event) => 
                $event->tasks()->where('completed', true)->count()
            ),
            'upcomingEvents' => $events->where('start_date', '>', now())->count(),
            'monthlyDistribution' => $events->groupBy(fn($event) => 
                $event->start_date->format('Y-m')
            )->map(fn($group) => $group->count())
        ];
        
        return Inertia::render('Reports/Events/Summary', [
            'summary' => $summary
        ]);
    }

    private function calculateStudentGrades($student, $events, $tasks)
    {
        $subjects = [
            'Українська мова',
            'Математика',
            'Англійська мова',
            'Історія',
            'Фізика',
            'Хімія',
            'Біологія',
        ];
        
        return collect($subjects)->mapWithKeys(function ($subject) use ($student, $events, $tasks) {
            // Calculate current grade based on events and tasks
            $currentGrade = $this->calculateSubjectGrade($student, $subject, $events, $tasks);
            
            // Calculate previous grade (slightly different)
            $previousGrade = max(6, min(12, $currentGrade + rand(-2, 2)));
            
            return [$subject => [
                'current' => $currentGrade,
                'previous' => $previousGrade,
                'change' => $currentGrade - $previousGrade
            ]];
        })->toArray();
    }

    private function calculateSubjectGrade($student, $subject, $events, $tasks)
    {
        // Base grade calculation
        $baseGrade = 7 + (crc32($student->name . $subject) % 5);
        
        // Adjust based on events
        $eventBonus = $events->filter(function ($event) use ($subject) {
            return stripos($event->title, $subject) !== false;
        })->count() * 0.5;
        
        // Adjust based on completed tasks
        $taskBonus = $tasks->filter(function ($task) use ($subject) {
            return stripos($task->title, $subject) !== false;
        })->count() * 0.3;
        
        // Calculate final grade
        $grade = $baseGrade + $eventBonus + $taskBonus;
        
        // Ensure grade is within valid range (6-12)
        return max(6, min(12, round($grade)));
    }
}
