<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            [
                'id' => (string)$this->id,
                'attributes' => [
                    'option' => $this->option,
                    'is_correct' => $this->is_correct,
                ],
                [
                    'relationships' => [
                        'id' => (string)$this->question_id,
                        'question' => $this->question->question_text,
                    ]
                ]
            ]
        ];
    }
}
