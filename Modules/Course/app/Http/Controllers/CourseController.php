<?php

namespace Modules\Course\App\Http\Controllers;

use App\Exceptions\CourseException;
use Modules\User\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Course\App\Models\Answer;
use Modules\Course\app\Models\Course;
use Modules\Course\app\Models\Episode;
use Modules\Course\App\Models\Question;
use Modules\Course\app\Models\Season;
use Modules\Course\App\Models\Vote;
use Modules\Student\app\Models\Student;
use Modules\User\app\Models\Log;

class CourseController extends Controller
{
    public function Add(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:3|max:2000',
            'price' => 'required|integer|min:4',
            'url' => 'required|min:4|max:50|unique:courses,url',
            'image' => 'nullable|mimes:png,jpg|max:5000',
        ]);
        $filename = null;
        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->image->getClientOriginalName();
            $request->image->storeAs('/images', $filename);
        }
        if (str_contains($request->url, ' ')) {
            return response()->json([
                'success' => true,
                'msg' => __('course::validation.spaced')
            ]);
        }
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'url' => $request->url,
            'image' => $filename,
            'user_id' => $user->id,
        ]);
        $log = new Log();
        $log->Log(
            $user->id,
            'courseAdd',
            $course->id,
            [
                'course' => $course,
            ]
        );
        return response()->json([
            'success' => true,
            'msg' => __('course::validation.course_added')
        ]);
    }

    public function Edit(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|integer',
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:3|max:2000',
            'price' => 'required|integer|min:4',
            'url' => 'required|min:4|max:50|unique:courses,url',
            'image' => 'nullable|mimes:png,jpg|max:2000'
        ]);

        if (!($course = Course::select('id', 'title', 'description', 'price', 'image', 'url')
            ->find($request->course_id))) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }
        if (str_contains($request->url, ' ')) {
            return response()->json([
                'success' => false,
                'msg' => __('course::validation.spaced')
            ]);
        }

        try {
            DB::transaction(function () use ($request, $course) {

                $oldCourse = [
                    'title' => $course->title,
                    'description' => $course->description,
                    'price' => $course->price,
                    'image' => $course->image,
                    'url' => $course->url,
                ];

                $user = Auth::user();
                $filename = $course->image;
                if ($request->hasFile('image')) {
                    if ($course->image)
                        Storage::delete('/images/' . $course->image);

                    $filename = time() . '_' . $request->image->getClientOriginalName();
                    $request->image->storeAs('images', $filename);
                }

                $data = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'image' => $filename,
                    'url' => $request->url
                ];

                $course->title = $request->title;
                $course->description = $request->description;
                $course->price = $request->price;
                $course->image = $filename;
                $course->url = $request->title;
                $course->save();

                $log = new Log();
                $log->Log(
                    $user->id,
                    'courseEdit',
                    $course->id,
                    [
                        'old_data' => $oldCourse,
                        'new_data' => $data,
                    ]
                );
            });

            return [
                'success' => true,
                'msg' => __('course::validation.course_edited')
            ];
        } catch (CourseException) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_editError')
            ];
        }
    }

    public function Delete(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
        ]);
        $user = Auth::user();

        if (!$course = Course::select('id', 'title', 'description', 'price', 'image', 'url')
            ->find($request->course_id)) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }

        $oldCourse = [
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'image' => $course->image,
            'url' => $course->url,
        ];

        $log = new Log();
        $log->Log(
            $user->id,
            'courseDelete',
            $course->id,
            [
                'old_data' => $oldCourse,
            ]
        );

        $course->delete();

        return [
            'success' => true,
            'msg' => __('course::validation.course_deleted')
        ];
    }

    #مدرس لیست دوره‌هایی که ساخته رو ببینه
    public function TeacherCourses()
    {
        $userId = Auth::id();

        if (!($teacherCourses = Course::select('id', 'user_id')
            ->where('user_id', $userId)
            ->get())) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }

        return response()->json([
            'teacherCourses' => $teacherCourses
        ]);
    }

    #مدرس لیست دانشجوهای خود را در دوره های مختلف ببینه
    public function TeacherStudents()
    {
        $teacherId = Auth::id();

        $courses = Course::select('id', 'user_id', 'title')
            ->where('user_id', $teacherId)
            ->get()
            ->keyBy('id')
            ->toArray();

        $studentRecords = Student::select('id', 'user_id', 'course_id')
            ->whereIn('course_id', array_column($courses, 'id'))
            ->get()
            ->toArray();

        $users = User::select('id', 'firstname')
            ->whereIn('id', array_column($studentRecords, 'user_id'))
            ->get()
            ->keyBy('id')
            ->toArray();

        $results = [];

        foreach ($studentRecords as $studentRecord) {

            $results[] =  [
                'courseTitle' => $courses[$studentRecord['course_id']]['title'],
                'studentName' => $users[$studentRecord['user_id']]['firstname']
            ];
        }
        return response()->json([
            'course_students' => $results
        ]);
    }

    #دانشجو فقط به جلسات دوره‌هایی که ثبت‌نام کرده دسترسی داره
    public function Show($coursePath)
    {
        if (!($course = Course::select('id', 'title', 'description', 'url')
            ->where('url', $coursePath)
            ->first())) {
            return [
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ];
        }

        $seasons = Season::select('id', 'title', 'course_id', 'season_number')
            ->where('course_id', $course->id)
            ->orderBy('season_number', 'asc')
            ->get();

        $episodes = Episode::select('id', 'title', 'season_id', 'episode_number')
            ->whereIn('season_id', $seasons->pluck('id'))
            ->orderBy('episode_number', 'asc')
            ->get();

        $groupedEpisodes = $episodes->groupBy('season_id');

        $result = [
            'success' => true,
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
            ],
            'seasons' => [],
        ];

        foreach ($seasons as $season) {
            $seasonData = [
                'season_id' => $season->id,
                'season_title' => $season->title,
                'episodes' => [],
            ];

            if (isset($groupedEpisodes[$season->id])) {
                foreach ($groupedEpisodes[$season->id] as $episode) {
                    $seasonData['episodes'][] = [
                        'id' => $episode->id,
                        'title' => $episode->title,
                        'episode_number' => $episode->episode_number,
                    ];
                }
            }
            $result['seasons'][] = $seasonData;
        }
        return response()->json($result);
    }

    public function QuestionAnswers()
    {
        $questions = Question::select('id', 'user_id', 'episode_id', 'question')
            ->get()
            ->keyBy('id');

        $episodes = Episode::select('id', 'title')
            ->get()
            ->keyBy('id')
            ->toArray();

        $answers = Answer::select('id', 'user_id', 'question_id', 'answer')
            ->whereIn('question_id', $questions->pluck('id'))
            ->get()
            ->groupBy('question_id')
            ->toArray();

        $questionsArray = $questions->toArray();

        $users =
            User::whereIn(
                'id',
                array_merge(
                    array_column($questionsArray, 'user_id'),
                    array_column($answers, 'user_id')
                )
            )
            ->get()
            ->keyBy('id')
            ->toArray();

        $questionResult = [];
        foreach ($questions as $question) {

            $questionUser = $users[$question['user_id']];
            $episode = $episodes[$question['episode_id']];

            $questionResult[] = [
                'episode_id' => $episode['id'],
                'episode_title' => $episode['title'],
                'question_id' => $question['id'],
                'question_text' => $question['question'],
                'question_userId' => $question['user_id'],
                'question_userName' => $questionUser['firstname'] . ' ' . $questionUser['lastname'],
                'question_userEmail' => $questionUser['email'],
                'answers' => []
            ];
            foreach (($answers[$question->id] ?? []) as $answer) {
                $answerUser = $users[$answer['user_id']];
                $answerResult[$answer['question_id']]['answers'][] = [

                    'answer_id' => $answer['id'],
                    'answer_text' => $answer['answer'],
                    'answer_userId' => $answer['user_id'],
                    'answer_userName' => $answerUser['firstname'] . ' ' . $answerUser['lastname'],
                    'answer_userEmail' => $questionUser['email'],
                ];
            }
        }
        return response()->json([
            'questions' => $questionResult,
            'answers' => $answerResult,
        ]);
    }

    public function CourseQuestionsAnswers(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
        ]);

        if (!($course = Course::select('id', 'title')->find($request->course_id))) {
            return response()->json([
                'success' => false,
                'msg' => __('course::validation.course_notFound')
            ]);
        }

        $seasons =
            Season::select('id', 'title', 'course_id')
            ->where('course_id', $course->id)
            ->get()
            ->keyBy('id')
            ->toArray();

        $episodes =
            Episode::select('id', 'title', 'season_id')
            ->whereIn('season_id', array_column($seasons, 'id'))
            ->get()
            ->keyBy('id')
            ->toArray();

        $questions =
            Question::select('id', 'user_id', 'episode_id', 'question')
            ->whereIn('episode_id', array_column($episodes, 'id'))
            ->get()
            ->keyBy('id')
            ->toArray();

        $answers =
            Answer::select('id', 'user_id', 'question_id', 'answer')
            ->whereIn('question_id', array_column($questions, 'id'))
            ->get()
            ->toArray();

        $users =
            User::whereIn(
                'id',
                array_merge(
                    array_column($questions, 'user_id'),
                    array_column($answers, 'user_id')
                )
            )
            ->get()
            ->keyBy('id')
            ->toArray();

        $results = [
            'course_id' => $course->id,
            'course_name' => $course->title,
            'questions' => []
        ];

        foreach ($questions as $question) {
            $questionUser = $users[$question['user_id']];
            $episode = $episodes[$question['episode_id']];

            $results[] = [
                'episode_id' => $episode['id'],
                'episode_title' => $episode['title'],
                'question_id' => $question['id'],
                'question_text' => $question['question'],
                'question_userId' => $question['user_id'],
                'question_userName' => $questionUser['firstname'] . ' ' . $questionUser['lastname'],
                'question_userEmail' => $questionUser['email'],
                'answers' => []
            ];
        }
        foreach ($answers as $answer) {
            $answerUser = $users[$answer['user_id']];
            $results[$answer['question_id']]['answers'][] = [
                $results['answers'][] = [
                    'answer_id' => $answer['id'],
                    'answer_text' => $answer['answer'],
                    'answer_userId' => $answer['user_id'],
                    'answer_userName' => $answerUser['firstname'] . ' ' . $answerUser['lastname'],
                    'answer_userEmail' => $answerUser['email'],
                ]
            ];
        }

        return response()->json($results);
    }

    public function TopAnswer()
    {
        $answers = Answer::select('id', 'answer')
            ->get()
            ->toArray();

        $votes = Vote::where('vote', 1)
            ->pluck('answer_id')
            ->toArray();

        $likeCounts = [];
        foreach ($votes as $vote) {
            $likeCounts[$vote] = ($likeCounts[$vote] ?? 0) + 1;
        }

        $results = [];
        foreach ($answers as $answer) {
            $answerId = $answer['id'];
            $likeCount = $likeCounts[$answerId] ?? 0;
            if ($likeCount >= 2) {
                $results[] = [
                    'answer_id' => $answerId,
                    'answer_text' => $answer['answer'],
                    'likes' => $likeCount
                ];
            }
        }

        return response()->json([
            'topAnswers' => $results
        ]);
    }
}
