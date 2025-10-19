<?php

namespace Modules\Student\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Course\App\Models\Course;
use Modules\Student\App\Models\Student;
use Modules\User\app\Models\Log;

class StudentController extends Controller
{

    public function Register(Request $request)
    {
        $request->validate([
            'course' => 'required|integer',
        ]);

        if (!($course = Course::select('id')->find($request->course))) {
            return [
                'success' => false,
                'msg' => __('student::validation.course_notFound'),
            ];
        }
        $user_id = Auth::id();

        if (Student::select('id')
            ->where([
                'user_id' => $user_id,
                'course_id' => $course->id
            ])
            ->first()
        ) {
            return [
                'success' => true,
                'msg' => __('student::validation.already_registered')
            ];
        }
        Student::create([
            'user_id'   => $user_id,
            'course_id' => $course->id,
        ]);
        $log = new Log();
        $log->Log(
            $user_id,
            'courseRegister',
            $course->id,
            []
        );
        return [
            'success' => true,
            'msg' => __('student::validation.success_registrater')
        ];
    }

    #دانشجو لیست دوره‌هایی که خریده رو ببینه
    public function StudentCourses(Request $request)
    {
        $request->validate([
            'user_id' => 'integer',
        ]);
        $userId = Auth::id();
        $courseIds = [];
        $studentCourses = Student::select('course_id')
            ->where('user_id', $userId)
            ->get();
        foreach ($studentCourses as $studentCourse) {
            $courseIds[] = $studentCourse->course_id;
        }

        $courses = Course::select('id', 'title', 'description', 'price')
            ->whereIn('id', $courseIds)
            ->get();
        return response()->json([
            'courses' => $courses
        ]);
    }
}
