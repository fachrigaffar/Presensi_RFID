<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;

class AttendanceApiController extends Controller {
    public function index() {
        $attendances = Attendance::with('student')->latest()->get();
        return response()->json($attendances);
    }
    public function store(Request $request) {
        $request->validate([
            'uid' => 'required',
        ]);

        // Find the student by UID
        $student = Student::where('uid', $request->uid)->first();

        if (!$student) {
            return response()->json(['message' => 'Student Not Found'], 404);
        }

        // Record attendance
        Attendance::create(['student_id' => $student->id]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'student' => [
                'name' => $student->name,
                'uid' => $student->uid,
                // 'attendance_time' => $attendance->created_at->toDateTimeString(),
        ]
        ], 201);
    }
}