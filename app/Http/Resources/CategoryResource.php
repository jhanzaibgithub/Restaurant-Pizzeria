<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Model\Translation;
class CategoryResource extends JsonResource
{
    public function toArray($request)
{		
    $translations = Translation::where('translationable_id', $this->id)
        ->where('translationable_type', 'App\\Model\\Category') // Filter in the query itself
        ->get();
    
    return [
        'id' => $this->id,
        'branch_id' => $this->branch_id,
        'name' => $this->name,
        'parent_id' => $this->parent_id,
        'position' => $this->position,
        'status' => $this->status,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'image' => env('APP_URL') . Storage::url('category/' . $this->image),
        'banner_image' => env('APP_URL') . Storage::url('banner/' . $this->banner_image),
        'priority' => $this->priority,
        'childes' => CategoryResource::collection($this->childes),
        'translations' => $translations->map(function ($translation) {
            return [
                'id' => $translation->id,
                'translationable_type' => $translation->translationable_type,
                'translationable_id' => $translation->translationable_id,
                'locale' => $translation->locale,
                'name' => $translation->value,
            ];
        }),
    ];
}

}
