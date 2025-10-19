<?php

namespace Modules\Course\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Student\App\Models\Student;
use Symfony\Component\HttpFoundation\Response;

class AccessCourseMiddleware
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $courseId = $request->id;

        if (!($user = $request->user())) {
            return response()->json([
                'success' => true,
                'msg' => __('course::validation.login_error')
            ]);
        }

        if (!($isRegistered = Student::where([
            'user_id' => $user->id,
            'course_id' => $courseId,
        ])->exists())) {
            return response()->json([
                'success' => false,
                'msg' => __('course::validation.access_showCourse')
            ]);
        }
        return $next($request);
    }
}
