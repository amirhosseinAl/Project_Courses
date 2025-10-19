<?php

namespace Modules\Course\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\App\Models\Course;
use Modules\Course\App\Models\Season;
use Modules\User\App\Models\Log;

class SeasonController extends Controller
{

    public function Add(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|min:3|max:100',
            'season_number' => 'integer|min:1|max:4',
        ]);
        $user = Auth::user();

        if (!($course = Course::select('id')->find($request->course_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }

        if ($course->user_id !== $user->id) {
            return [
                'success' => false,
                'msg' => __('course::validation.access_editCourse')
            ];
        }

        $season = Season::create([
            'title' => $request->title,
            'course_id' => $course->id,
            'season_number' => $request->season_number,
        ]);

        $log = new Log();
        $log->Log(
            $user->id,
            'seasonAdd',
            $season->id,
            [
                'course_id' => $request->course_id,
                'season' => $season,
            ]
        );
        return [
            'success' => true,
            'msg' => __('course::validation.season_added'),
        ];
    }

    public function Edit(Request $request)
    {
        $request->validate([
            'season' => 'required|integer',
            'title'  => 'required|min:3|max:100',
            'season_number' => 'integer|min:1|max:4',
        ]);

        if (!($season = Season::find($request->season))) {
            return [
                'success' => false,
                'msg' => __('course::validation.season_notFound')
            ];
        }

        if (!($course = Course::select('id', 'user_id')->find($season->course_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }
        $user = Auth::user();

        if ($course->user_id !== $user->id) {
            return [
                'success' => false,
                'msg' => __('course::validation.access_editCourse')
            ];
        }

        $oldSeason = [
            'season_id' => $season->id,
            'season_title' => $season->title,
            'season_number' => $season->season_number,
        ];

        $data = [
            'title' => $request->title,
            'season_number' => $request->season_number,
        ];

        $season->title = $request->title;
        $season->season_number = $request->season_number;
        $season->save();

        $log = new Log();
        $log->Log(
            $user->id,
            'seasonEdit',
            $season->id,
            [
                'course_id' => $course->id,
                'old_data'  => $oldSeason,
                'new_data'  => $data,
            ]
        );
        return [
            'success' => true,
            'msg' => __('course::validation.season_edited'),
        ];
    }

    public function Delete(Request $request)
    {
        $request->validate([
            'season' => 'required|integer',
        ]);

        if (!($season = Season::select('id', 'title', 'course_id', 'season_number')->find($request->season))) {
            return [
                'success' => false,
                'msg' => __('course::validation.season_notFound')
            ];
        }
        $user = Auth::user();

        $oldSeason = [
            'season_id'   => $season->id,
            'season_title' => $season->title,
            'course_id'   => $season->course_id,
            'season_number' => $season->season_number,
        ];
        $log = new Log();
        $log->Log(
            $user->id,
            'seasonDelete',
            $season->id,
            [
                'old_data'   => $oldSeason,
            ]
        );
        $season->delete();
        return [
            'success' => true,
            'msg' => __('course::validation.season_deleted')
        ];
    }
}
