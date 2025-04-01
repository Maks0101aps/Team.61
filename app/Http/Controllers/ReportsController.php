<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        return Inertia::render('Reports/Index');
    }

    /**
     * Display student attendance report.
     */
    public function studentAttendance()
    {
        // To be implemented
        return Inertia::render('Reports/Students/Attendance');
    }

    /**
     * Display student performance report.
     */
    public function studentPerformance()
    {
        // To be implemented
        return Inertia::render('Reports/Students/Performance');
    }

    /**
     * Display student list report.
     */
    public function studentList()
    {
        // To be implemented
        return Inertia::render('Reports/Students/List');
    }

    /**
     * Display teacher workload report.
     */
    public function teacherWorkload()
    {
        // To be implemented
        return Inertia::render('Reports/Teachers/Workload');
    }

    /**
     * Display teacher schedule report.
     */
    public function teacherSchedule()
    {
        // To be implemented
        return Inertia::render('Reports/Teachers/Schedule');
    }

    /**
     * Display teacher list report.
     */
    public function teacherList()
    {
        // To be implemented
        return Inertia::render('Reports/Teachers/List');
    }

    /**
     * Display event calendar report.
     */
    public function eventCalendar()
    {
        // To be implemented
        return Inertia::render('Reports/Events/Calendar');
    }

    /**
     * Display event attendance report.
     */
    public function eventAttendance()
    {
        // To be implemented
        return Inertia::render('Reports/Events/Attendance');
    }

    /**
     * Display event summary report.
     */
    public function eventSummary()
    {
        // To be implemented
        return Inertia::render('Reports/Events/Summary');
    }
}
