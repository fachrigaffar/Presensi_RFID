<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Laravel\Sanctum\HasApiTokens;


class StudentApiController extends Controller
{
    public function index() {
        // Include attendance count with each student
        $students = Student::withCount('attendances')->get();
        return response()->json($students);
    }

    public function store(Request $request) {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'uid' => 'required|string|max:255|unique:students',
        ]);

        // Create student
        $student = Student::create([
            'name' => $request->name,
            'uid' => $request->uid,
        ]);

        // Return success response
        return response()->json(['message' => 'Student Registered Successfully!', 'student' => $student], 201);
    }

}
