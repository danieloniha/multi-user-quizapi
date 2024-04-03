<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\Subject;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    use HttpResponses;

    public function index(Subject $subject)
    {

        $userSubject = Auth::user()->subjects()->where('subjects.id', $subject->id)->exists();

        if (!$userSubject) {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $quizzes = Quiz::where('subject_id', $subject->id)->get();
        return QuizResource::collection($quizzes);
    }

    public function store(QuizRequest $request, Subject $subject)
    {

        $userSubject = Auth::user()->subjects()->where('subjects.id', $subject->id)->exists();

        if (!$userSubject) {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $request->validated($request->all());

        $quiz = Quiz::create([
            'subject_id' => $subject->id,
            'time_limit' => $request->time_limit,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'no_of_questions' => $request->no_of_questions,
            'instructions' => $request->instructions,
        ]);

        return new QuizResource($quiz);
    }

    public function show(Subject $subject, Quiz $quiz)
    {
        $userSubject = Auth::user()->subjects()->where('subjects.id', $subject->id)->exists();

        if ($userSubject) {
            if ($quiz->subject_id != $subject->id) {
                // If the quiz does not belong to the subject, return an error response
                return response()->json(['message' => 'This quiz does not belong to the specified subject'], 403);
            }
            return new QuizResource($quiz);
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
    }

    public function update(Request $request, Subject $subject, Quiz $quiz)
    {
        $userSubject = Auth::user()->subjects()->where('subjects.id', $subject->id)->exists();

        if ($userSubject) {

            if ($quiz->subject_id != $subject->id) {
                return response()->json(['message' => 'This quiz does not belong to the specified subject'], 403);
            }
            $quiz->update($request->all());

            return new QuizResource($quiz);
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
    }

    public function destroy(Subject $subject, Quiz $quiz)
    {

        $userSubject = Auth::user()->subjects()->where('subjects.id', $subject->id)->exists();

        if ($userSubject) {

            if ($quiz->subject_id != $subject->id) {
                return response()->json(['message' => 'This quiz does not belong to the specified subject'], 403);
            }
            $quiz->delete();
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
    }


    public function getQuizzesForSubject(Subject $subject)
{
    $user = auth()->user();

    // Ensure the user is a student and is enrolled in the subject
    if ($user->role == 'student' && $user->subjects->contains('id', $subject->id)) {

        $quizzes = Quiz::where('subject_id', $subject->id)->get();
        return QuizResource::collection($quizzes);
        //return response()->json($quizzes);

    } else {
        return response()->json(['message' => 'You are not authorized to view these quizzes.'], 403);
    }
}

}
