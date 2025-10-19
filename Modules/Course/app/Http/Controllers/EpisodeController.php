<?php

namespace Modules\Course\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Course\app\Models\Episode;
use Modules\Course\app\Models\Season;
use Modules\User\app\Models\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Course\App\Models\Answer;
use Modules\Course\App\Models\Course;
use Modules\Course\App\Models\Question;
use Modules\Course\App\Models\Vote;

class EpisodeController extends Controller
{
    public function Add(Request $request)
    {
        $request->validate([
            'season_id' => 'required|integer',
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:2000',
            'episode_number' => 'integer|min:1|max:4',
            // 'video' => 'required|mimes:mp4,mov|max:51200',
        ]);
        $user = Auth::user();
        if (!($season = Season::select('id')->find($request->season_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.season_notFound')
            ];
        }
        $filename = time() . '_' . $request->video
            ->getClientOriginalName();
        $videoPath = $request->file('video')
            ->storeAs('episodes/videos', $filename);

        $episode = Episode::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoPath,
            'season_id' => $season->id,
            'episode_number' => $request->episode_number,
        ]);

        $log = new Log();
        $log->Log(
            $user->id,
            'episodeAdd',
            $episode->id,
            [
                'season_id' => $request->season_id,
                'episode' => $episode,
            ]
        );
        return [
            'success' => true,
            'msg' => __('course::validation.episode_added')
        ];
    }

    public function Edit(Request $request)
    {
        $request->validate([
            'episode_id' => 'required|integer',
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:2000',
            'video' => 'mimes:mp4,mov|max:51200',
            'episode_number' => 'integer|min:1|max:4',
        ]);

        $user = Auth::user();
        if (!($episode = Episode::select('id', 'title', 'description', 'video_path', 'season_id', 'episode_number')
            ->find($request->episode_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.episode_notFound')
            ];
        }

        if (!($season = Season::select('id')->find($episode->season_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.season_notFound')
            ];
        }

        $oldepisode = [
            'title' => $episode->title,
            'description' => $episode->video_path,
            'episode_number' => $episode->episode_number,
        ];

        $videoPath = $episode->video_path;

        if ($request->hasFile('video')) {
            Storage::delete('episodes/videos/' . $episode->video);
            $filename = time() . '_' . $request->video
                ->getClientOriginalName();
            $videoPath = $request->file('video')
                ->storeAs('episodes/videos', $filename);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoPath,
            'season_id' => $season->id,
            'episode_number' => $request->episode_number
        ];

        $episode->title = $request->title;
        $episode->description = $request->description;
        $episode->video_path = $videoPath;
        $episode->season_id = $season->id;
        $episode->episode_number = $request->episode_number;
        $episode->save();

        $log = new Log();
        $log->Log(
            $user->id,
            'episodeEdit',
            $episode->id,
            [
                'season_id'   => $episode->season_id,
                'old_data'   => $oldepisode,
                'new_data'   => $data,
            ]
        );

        return [
            'success' => true,
            'msg' => __('course::validation.episode_edited')
        ];
    }

    public function Delete(Request $request)
    {
        $request->validate([
            'episode_id' => 'required|integer',
        ]);

        if (!($episode = Episode::select('id', 'title', 'description', 'video_path', 'season_id', 'episode_number')
            ->find($request->episode_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.episode_notFound')
            ];
        }
        $oldepisode = [
            'title' => $episode->title,
            'description' => $episode->description,
            'video_path' => $episode->video_path,
            'episode_number' => $episode->episode_number,
        ];
        $user = Auth::user();

        $log = new Log();
        $log->Log(
            $user->id,
            'episodeDelete',
            $episode->id,
            [
                'old_data' => $oldepisode,
            ]
        );

        $episode->delete();

        return [
            'success' => true,
            'msg' => __('course::validation.episode_deleted')
        ];
    }

    public function Show($coursePath, $episodeNumber)
    {

        if (!($course = Course::select('id' , 'url')
                ->where('url', $coursePath)
            ->first())) {
            return response()->json([
                'success' => false,
                'msg' => __('course::validation.course_notFound'),
            ]);
        }

        if (!($seasons = Season::select('id', 'course_id')->where('course_id', $course->id)->get())) {
            return [
                'success' => false,
                'msg' => __('course::validation.season_notFound')
            ];
        }

        if (!($episode = Episode::select('id', 'title', 'description', 'episode_number', 'season_id' , 'video_path')
                ->whereIn('season_id', $seasons->pluck('id'))
                ->where('episode_number', $episodeNumber)
            ->first())) {
            return [
                'success' => false,
                'msg' => __('course::validation.episode_notFound')
            ];
        }

        if (!($questions = Question::select('id', 'episode_id', 'question')
            ->where('episode_id', $episode->id)
            ->get())) {
            return [
                'success' => false,
                'msg' => __('course::validation.episode_noneQuestion')
            ];
        }

        $answers = Answer::select('id', 'question_id', 'user_id', 'answer', 'created_at')
            ->whereIn('question_id', $questions->pluck('id'))
            ->get();

        $answersVotes = [];
        foreach ($answers as $answer) {
            $likesCount = Vote::where([
                ['answer_id', $answer->id],
                ['vote', Vote::TYPE_LIKE],
            ])->count();

            $dislikesCount = Vote::where([
                ['answer_id', $answer->id],
                ['vote', Vote::TYPE_DISLIKE],
            ])->count();


            $answersVotes[] = [
                'id' => $answer->id,
                'question_id' => $answer->question_id,
                'user_id' => $answer->user_id,
                'answer' => $answer->answer,
                'created_at' => $answer->created_at,
                'likes_count' => $likesCount,
                'dislikes_count' => $dislikesCount,
            ];
        }

        $groupedAnswers = $answers->groupBy('question_id');

        $questionsAnswers = [];
        foreach ($questions as $question) {
            $questionsAnswers[] = [
                'id' => $question->id,
                'question' => $question->question,
                'answer' => $answer->answer,
            ];

            return response()->json([
                'success' => true,
                'episode' => $episode,
                'questions' => $questions,
                'answers' => $groupedAnswers,
            ]);
        }
    }
}
