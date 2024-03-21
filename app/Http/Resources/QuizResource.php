<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => (string)$this->id,
            'attributes' => [
                'time_limit' => $this->time_limit,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'no_of_questions' => $this->no_of_questions,
            ],
            'relationships' => [
                'id' => (string)$this->subject_id,
                'subject name' => $this->subject->name,
                'co-lecturer' => $this->subject->co_lecturer,
            ]
        ];
    }
}
