<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAnswerRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\StudentAnswerResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentAnswer;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentAnswerController extends Controller
{
    public function index($quizId, $questionId)
    {
        // Retrieve the question
        $question = Question::findOrFail($questionId);

        // Check if the provided quiz ID matches the quiz ID of the question
        if ($question->quiz_id != $quizId) {
            return response()->json(['message' => 'Question not found in the specified quiz'], 404);
        }

        // Retrieve all student answers for the specified question
        $answers = StudentAnswer::where('question_id', $questionId)->get();

        // Return the answers as a resource collection
        return StudentAnswerResource::collection($answers);
    }

    public function store(StudentAnswerRequest $request, Quiz $quiz, Question $question)
    {

        $user = auth()->user();
        $isAuthorized = $user->subjects()->where('subjects.id', $quiz->subject_id)->exists();

        if (!$isAuthorized) {
            return response()->json(['message' => 'You are not authorized to access this'], 403);
        }

        $request->validated($request->all());
        $StudentAnswer = StudentAnswer::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'answer_id' => $request->answer_id,
            'is_correct' => $request->is_correct
        ]);

        return new StudentAnswerResource($StudentAnswer);
    }
}
