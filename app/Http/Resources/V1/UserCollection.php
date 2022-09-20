<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public static $wrap = 'users';

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }

    public function withResponse($request, $response)
    {

        // Приводим response в соответствие с образцом из ТЗ (с соблюдением порядка свойств)
        // если не заморачиваться с порядком, все становится гораздо проще и короче))

        $jsonResponse = json_decode($response->getContent(), true);
        $hasPagination = isset($jsonResponse['links']);
        unset($jsonResponse['links'], $jsonResponse['meta']);
        $formattedJsonResponse = [
            'success' => $jsonResponse['success'],
            'page' => $hasPagination ? $this->currentPage() : 1,
            'total_pages' => $hasPagination ? $this->lastPage() : 1,
            'total_users' => $hasPagination ? $this->total() : null,
            'count' => $this->count(),
            'links' => [
                'next_link' => $hasPagination ? ($this->nextPageUrl() ?? null) : null,
                'prev_link' => $hasPagination ? ($this->previousPageUrl() ?? null) : null,
            ],
            'users' => $jsonResponse['users'],
        ];
        $response->setContent(json_encode($formattedJsonResponse));
    }
}
