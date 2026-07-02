<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\Translation; 

class AddonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Fetch all translations related to this Addon
        $translations = Translation::where('translationable_id', $this->id)
        ->where('translationable_type', 'App\\Model\\AddOn') 
            ->get()
            ->map(function ($translation) {
                return [
                    'translationable_type' => $translation->translationable_type,
                    'translationable_id' => $translation->translationable_id,
                    'locale' => $translation->locale,
					 'name' => $translation->value
                ];
            });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'tax' => $this->tax,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'translations' => $translations,
        ];
    }
}