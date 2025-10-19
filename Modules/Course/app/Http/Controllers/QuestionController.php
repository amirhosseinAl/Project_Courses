<?php

namespace Modules\Course\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Course\App\Models\Answer;
use Modules\Course\App\Models\Episode;
use Modules\Course\App\Models\Question;
use Modules\User\app\Models\Log;

class QuestionController extends Controller
{
    public function Add(Request $request)
    {
        $request->validate([
            'episode'  => 'required|integer',
            'question' => 'required|string|max:5000',
        ]);

        if (!($episode = Episode::select('id')->find($request->episode))) {
            return [
                'success' => false,
                'msg' => __('course::validation.episode_notFound')
            ];
        }
        $user = Auth::user();
        $question = Question::create([
            'user_id' => $request->user()->id,
            'episode_id' => $episode->id,
            'question' => $request->question,
        ]);

        $log = new Log();
        $log->Log(
            $user->id,
            'answerAdd',
            $question->id,
            [
                'episode_id' => $episode->id,
            ]
        );

        return response()->json([
            'success' => true,
            'msg' => __('course::validation.question_added'),
            'data' => $question,
        ]);
    }

    public function Edit(Request $request)
    {
        if (Answer::where('question_id', $request->route('question'))->exists()) {
            return [
                'success' => true,
                'msg' => __('course::validation.question_lockEdit'),
            ];
        }

        $request->validate([
            'question' => 'required|string|max:5000',
        ]);

        if (!($question = Question::find($request->route('question')))) {
            return [
                'success' => false,
                'msg' => __('course::validation.question_notFound')
            ];
        }
        $user = Auth::user();
        $oldQuestion = $question->question;

        $question->question = $request->question;
        $question->save();

        $log = new Log();
        $log->Log(
            $user->id,
            'questionEdit',
            $question->id,
            [
                'old_question' => $oldQuestion,
            ]
        );
        return response()->json([
            'success' => true,
            'msg' => __('course::validation.question_edited')
        ]);
    }

    public function Delete(Request $request)
    {
        if (Answer::where('question_id', $request->question)->exists()) {
            return [
                'success' => false,
                'msg' => __('course::validation.question_lockdelete'),
            ];
        }
        $request->validate([
            'question' => 'required|string|max:5000',
        ]);

        $question = Question::select('id', 'question')
            ->find($request->question);

        $oldQuestion = $question->question;
        $user = Auth::user();
        $log = new Log();
        $log->Log(
            $user->id,
            'questionDelete',
            $question->id,
            [
                'old_question' => $oldQuestion,
            ]
        );

        $question->delete();

        return [
            'success' => true,
            'msg' => __('course::validation.question_deleted'),
        ];
    }
}
