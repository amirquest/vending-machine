<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Media $this */
        return [
            'mime_type' => $this->mime_type,
            'url' => config('app.env') === 'testing' ?
                $this->getFullUrl() :
                $this->getTemporaryUrl(now()->addHours(4)),
        ];
    }
}
