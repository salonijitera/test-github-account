<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\ResourceResponse;

class BaseJsonCollection extends ResourceCollection
{
    public function with($request)
    {
        return [
            'success' => true,
            'total_pages' => $this->lastPage(), // @phpstan-ignore-line
            'total_records' => $this->total(), // @phpstan-ignore-line
        ];
    }

    public function toResponse($request)
    {
        return (new ResourceResponse($this))->toResponse($request);
    }
}
