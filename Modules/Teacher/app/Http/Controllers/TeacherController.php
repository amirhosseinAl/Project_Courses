<?php

namespace Modules\Teacher\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Course\App\Models\Course;
use Modules\Student\App\Models\Student;
use Modules\User\app\Models\Log;
use Modules\User\App\Models\User;

class TeacherController extends Controller
{
    public function AddByTeacher(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|min:1',
            'email'     => 'required|email|max:255',
        ]);
        $teacher_id = Auth::id();
        $course_id = $request->course_id;

        if (!($course = Course::select('id', 'user_id')
            ->where([
                'id'       => $course_id,
                'user_id'  => $teacher_id
            ])
            ->first())) {
            return [
                'success' => false,
                'msg' => __('teacher::validation.not_teacher')
            ];
        }

        if (!($studentUser = User::select('id', 'email')
            ->where('email', $request->email)
            ->first())) {
            return [
                'success' => true,
                'msg' => __('teacher::validation.email_notFound')
            ];
        }

        if (Student::select('id')
            ->where([
                'user_id' => $studentUser->id,
                'course_id' => $course_id
            ])
            ->first()) {
            return [
                'success' => true,
                'msg' => __('teacher::validation.student_alreadyRegistered')
            ];
        }

        Student::create([
            'user_id'   => $studentUser->id,
            'course_id' => $course_id,
        ]);

        $log = new Log();
        $log->Log(
            $teacher_id,
            'courseAddByTeacher',
            $course_id,
            [
                'student_id' => $studentUser->id,
            ]
        );
        return [
            'success' => true,
            'msg' => __('teacher::validation.student_registered')
        ];
    }
}
