<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectsController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->subjects) {
            // Assuming subject_user is a many-to-many relationship
            $user = User::where('id', auth()->id())->with('subjects.users')->first();

            $subjects = $user->subjects;
            return SubjectResource::collection($subjects);
        } else {
            // Handle the case where subject_user is null
            return response()->json(['message' => 'No subjects found for the user.'], 404);
        }
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $request->validated($request->all());

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->co_lecturer = $request->co_lecturer;
        $subject->save();

        $user = auth()->user();
        $user->subjects()->attach($subject->id);

        return new SubjectResource($subject);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return $this->isNotAuthorized($subject) ? $this->isNotAuthorized($subject) : new SubjectResource($subject);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        if (!Auth::user()->subjects->contains($subject)) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }

        $subject->update($request->all());

        return new SubjectResource($subject);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        return $this->isNotAuthorized($subject) ? $this->isNotAuthorized($subject) : $subject->delete();
    }

    private function isNotAuthorized($subject)
    {
        if (!Auth::user()->subjects->contains($subject)) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }


    public function getStudentSubjects()
    {
        $user = auth()->user();
        
        // Ensure the user is a student
        if ($user->role == 'student') {
            $subjects = $user->subjects; // Assuming you've set up the many-to-many relationship
            return SubjectResource::collection($subjects);
            //return response()->json($subjects);
        } else {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }
}
