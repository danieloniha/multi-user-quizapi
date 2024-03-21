<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'question_text' => $this->question_text,
                'note_text' => $this->note_text ?? 'N/A',
            ],
            'relationships' => [
                'id' => (string)$this->quiz_id,
                'subject_name' => optional($this->quiz->subject)->name ?? 'N/A',
                'lecturer_name' => optional($this->quiz->subject->users->first())->name ?? 'N/A',
                'co_lecturer' => optional($this->quiz->subject)->co_lecturer ?? 'N/A',
                'time_limit' => $this->quiz->time_limit,
                'no_of_questions' => $this->quiz->no_of_questions,
            ]
        ];
    }
}
