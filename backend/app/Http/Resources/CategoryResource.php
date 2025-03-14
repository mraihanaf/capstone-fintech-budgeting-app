<?php

namespace App\Http\Resources;

use Illuminate\Http\ReportRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(ReportRequest $request): array
    {
        return parent::toArray($request);
    }
}
