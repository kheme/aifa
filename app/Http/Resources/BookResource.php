<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->when(! $this->wasRecentlyCreated || $this->exists, $this->id),
            'name'            => $this->name,
            'isbn'            => $this->isbn,
            'authors'         => new AuthorResource($this->getAuthors->pluck('name')),
            'number_of_pages' => $this->number_of_pages,
            'publisher'       => $this->publisher,
            'country'         => $this->country,
            'release_date'    => $this->release_date,
        ];
    }
}