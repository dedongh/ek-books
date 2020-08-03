<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BooksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>(string)$this->id,
            'type'=>'books',
            'attributes'=>[
                'title' => $this->title,
                'description' => $this->description,
                'year' => $this->year,
            ],

            'relationships'=>[
                'authors'=>[
                    'links' =>[
                        'self'=> route('books.relationships.authors',$this->id),
                        'related' => route('books.authors',$this->id),
                    ],
                    'data' => AuthorsIdentifierResource::collection(
                        $this->whenLoaded('authors')
                    ),
                ],
            ]
        ];
    }

    public function relations()
    {
        return [
            AuthorsResource::collection($this->whenLoaded('authors'))
        ];
    }

    public function with($request)
    {
        $with = [];

        if ($this->included($request)->isNotEmpty()) {
            $with['included'] = $this->included($request);
        }

        return $with;
    }

    public function included($request)
    {
        return collect($this->relations())
            ->filter(function ($resource){
                return $resource->collection != null;
            })
            ->flatMap->toArray($request);
    }
}
