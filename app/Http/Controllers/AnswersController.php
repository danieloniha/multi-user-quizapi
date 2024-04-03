<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswersController extends Controller
{
    use HttpResponses;

    public function index(Question $question)
    {
        //$questionId = Question::findOrFail($question);
        $user = auth()->user();

        $isAuthorized = $user->subjects()->where('subjects.id', $question->quiz->subject_id)->exists();

        if (!$isAuthorized) {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $answer = Answer::where('question_id', $question->id)->get();

        return AnswerResource::collection($answer);
    }

    public function store(AnswerRequest $request, Question $question)
    {

        $user = auth()->user();

        $isAuthorized = $user->subjects()->where('subjects.id', $question->quiz->subject_id)->exists();

        if (!$isAuthorized) {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $request->validated($request->all());
        $answer = Answer::create([
            'question_id' => $question->id,
            'option' => $request->option,
            'is_correct' => $request->is_correct
        ]);

        return new AnswerResource($answer);
    }

    public function show(Question $question, Answer $answer)
    {

        $user = auth()->user();

        $isAuthorized = $user->subjects()->where('subjects.id', $question->quiz->subject_id)->exists();

        if ($isAuthorized) {
            if ($answer->question_id != $question->id) {
                return response()->json(['message' => 'You are not authorized to access this answer'], 403);
            }
            return new AnswerResource($answer);
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
        
    }

    public function update(Request $request, Question $question, Answer $answer)
    {

        $user = auth()->user();

        $isAuthorized = $user->subjects()->where('subjects.id', $question->quiz->subject_id)->exists();

        if ($isAuthorized) {
            if ($answer->question_id != $question->id) {
                return response()->json(['message' => 'You are not authorized to access this answer'], 403);
            }
            $answer->update($request->all());
            return new AnswerResource($answer);
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
    }

    public function destroy(Question $question, Answer $answer)
    {

        $user = auth()->user();

        $isAuthorized = $user->subjects()->where('subjects.id', $question->quiz->subject_id)->exists();

        if ($isAuthorized) {
            if ($answer->question_id != $question->id) {
                return response()->json(['message' => 'You are not authorized to access this answer'], 403);
            }
            $answer->delete();
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

    }
}
