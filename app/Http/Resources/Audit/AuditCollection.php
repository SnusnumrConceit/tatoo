<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuditCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'last_page' => $this->lastPage()
        ];
    }
}
