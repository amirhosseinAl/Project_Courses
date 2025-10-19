<?php

namespace Modules\Course\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Course\App\Models\Answer;
use Modules\Course\App\Models\Vote;
use Modules\User\app\Models\Log;

class VoteController extends Controller
{

    public function Add(Request $request)
    {
        $request->validate([
            'vote' => 'required',
        ]);
        if (!($answer = Answer::find($request->route('answer')))) {
            return [
                'success' => false,
                'msg' => __('course::validation.answer_notFound')
            ];
        }
        $userId = Auth::id();
        try {
            
            $response = DB::transaction(function () use ($request, $answer, $userId) {
                $vote = Vote::where('user_id', $userId)
                    ->where('answer_id', $answer->id)
                    ->lockForUpdate()
                    ->first();

                if ($vote) {
                    if ($vote->vote == $request->vote) {
                        $log = new Log();
                        $log->Log(
                            $userId,
                            'vote.delete',
                            $answer->id,
                            [
                                'old_vote'  => $vote->vote,
                            ]
                        );

                        $vote->delete();

                        return response()->json([
                            'success' => true,
                            'msg' => __('course::validation.vote_deleted')
                        ]);
                    }

                    $log = new Log();
                    $log->Log(
                        $userId,
                        'voteUpdate',
                        $answer->id,
                        [
                            'old_vote'  => $vote->vote,
                            'new_vote'  => $request->vote,
                        ]
                    );

                    $vote->vote = $request->vote;
                    $vote->save();

                    return response()->json([
                        'success' => true,
                        'msg' => __('course::validation.vote_updated'),
                        'data'    => $vote,
                    ]);
                }

                $vote = Vote::create([
                    'user_id'   => $userId,
                    'answer_id' => $answer->id,
                    'vote'      => $request->vote,
                ]);

                $log = new Log();
                $log->Log(
                    $userId,
                    'voteAdd',
                    $vote->id,
                    [
                        'answer_id' => $answer->id,
                        'vote'      => $request->vote,
                    ]
                );
                return response()->json([
                    'success' => true,
                    'msg' => __('course::validation.vote_added'),
                    'data'    => $vote,
                ]);
            });

            return $response;
        } catch (\Exception $e) {
            $log = new Log();
            $log->Log(
                $userId,
                'voteError',
                $vote->id,
                [
                    'answer_id' => $answer->id,
                ]
            );

            return response()->json([
                'success' => false,
                'msg' => __('course::validation.vote_addError'),
            ]);
        }
    }
}
