<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionsController extends Controller
{
    use HttpResponses;

    public function index($quizId){

        $quiz = Quiz::findOrFail($quizId);

        $userSubject = Auth::user()->subjects->pluck('id');
        $isAuthorized = $userSubject->contains($quiz->subject_id);

        if(!$isAuthorized){
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $questions = Question::where('quiz_id', $quiz->id)->get();

        return QuestionResource::collection($questions);

    }

    public function store(StoreQuestionRequest $request, $quizId){
        
        $quiz = Quiz::findOrFail($quizId);

        $userSubject = Auth::user()->subjects->pluck('id');
        $isAuthorized = $userSubject->contains($quiz->subject_id);

        if(!$isAuthorized){
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
        $request->validated($request->all());

        $questionsCount = $quiz->question()->count();

        if($questionsCount < $quiz->no_of_questions){
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $request->question_text,
                'note_text' => $request->note_text
            ]);
    
            return new QuestionResource($question);
        } else {
            return response()->json(['message' => 'The maximum number of questions for this quiz has been reached.'], 403);
        }
        
    }

    public function show($quizId, Question $question){

        $quiz = Quiz::findOrFail($quizId);

        $userSubject = Auth::user()->subjects->pluck('id');
        $isAuthorized = $userSubject->contains($quiz->subject_id);

        if($isAuthorized){
            if($question->quiz_id != $quiz->id){
                return response()->json(['message' => 'You are not authorized to access this question'], 403);
            }
            return new QuestionResource($question);
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

    }

    public function update(Request $request, $quizId, Question $question){

        $quiz = Quiz::findOrFail($quizId);

        $userSubject = Auth::user()->subjects->pluck('id');
        $isAuthorized = $userSubject->contains($quiz->subject_id);

        if($isAuthorized){

            if($question->quiz_id != $quiz->id){
                return response()->json(['message' => 'You are not authorized to access this question'], 403);
            }

            $question->update($request->all());
            return new QuestionResource($question);

        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }
    }

    public function destroy($quizId, Question $question){

        $quiz = Quiz::findOrFail($quizId);

        $userSubject = Auth::user()->subjects->pluck('id');
        $isAuthorized = $userSubject->contains($quiz->subject_id);

        if($isAuthorized){

            if($question->quiz_id != $quiz->id){
                return response()->json(['message' => 'You are not authorized to access this question'], 403);
            }

            $question->delete();
            
        } else {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

    }
}

