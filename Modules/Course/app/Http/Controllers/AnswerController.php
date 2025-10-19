<?php

namespace Modules\Course\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\App\Models\Answer;
use Modules\Course\App\Models\Question;
use Modules\User\app\Models\Log;

class AnswerController extends Controller
{
    public function Add(Request $request)
    {
        $request->validate([
            'answer'  => 'required|string|max:5000',
            'question' => 'required|integer',
        ]);
        if (!($question = Question::select('id')->find($request->question))) {
            return [
                'success' => false,
                'msg'     => __('course::validation.question_notFound')
            ];
        }
        $answer = Answer::create([
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
            'answer' => $request->answer,
        ]);
        $log = new Log();
        $log->Log(
            $request->user()->id,
            'answerAdd',
            $answer->id,
            [
                'question_id' => $question->id,
                'data' => $answer,
            ]
        );
        return response()->json([
            'success' => true,
            'msg' => __('course::validation.answer_added'),
            'data' => $answer,
        ]);
    }

    public function Edit(Request $request)
    {
        $request->validate([
            'answer' => 'required|string|max:5000',
            'answer_id' => 'required|integer',
        ]);

        if (!($answer = Answer::select('id', 'user_id', 'answer')->find($request->answer_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.answer_notFound')
            ];
        }

        if ($request->user()->id !== $answer->user_id) {
            return response()->json([
                'success' => false,
                'msg' => __('course::validation.access_editAnswer')
            ]);
        }

        $oldAnswer = $answer->answer;
        $answer->answer = $request->answer;
        $answer->save();

        $log = new Log();
        $log->Log(
            $request->user()->id,
            'answerEdit',
            'پاسخ ویرایش شد',
            $answer->id,
            [
                'old_answer' => $oldAnswer,
                'new_answer' => $request->answer,
            ]
        );

        return response()->json([
            'success' => true,
            'msg' => __('course::validation.answer_edited')
        ]);
    }

    public function Delete(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|integer',
        ]);
        if (!($answer = Answer::select('id', 'user_id', 'answer')->find($request->answer_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.answer_notFound')
            ];
        }
        if ($request->user()->id !== $answer->user_id) {
            return [
                'success' => false,
                'msg' => __('course::validation.access_deleteAnswer')
            ];
        }

        $oldAnswer = $answer->answer;
        $answer->delete();

        $log = new Log();
        $log->Log(
            $request->user()->id,
            'answerDelete',
            'پاسخ حذف شد',
            $answer->id,
            [
                'old_answer' => $oldAnswer,
            ]
        );
        return response()->json([
            'success' => true,
            'msg' => __('course::validation.answer_deleted')
        ]);
    }
}
