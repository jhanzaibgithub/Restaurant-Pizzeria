<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\Translation;
use Storage;
use App\Model\Category;
use App\Model\AddOn;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
	
	public function toArray($request)
	{	
		$branch = $request->branch;
		
		$locale = $request->header('locale', 'en'); 
		
		// Fetch translations for the given product and locale
		$translations = Translation::where('translationable_id', $this->id)
			->get()
			->groupBy('locale')
			->map(function ($translationGroup) {
				return $translationGroup->pluck('value', 'key');
			});

		$category = Category::where('id', $this->category_id)->first(); // Use first() for a single result

		$decoded_category_ids = json_decode($this->category_ids, true); 
		$second_object = isset($decoded_category_ids[1]) ? $decoded_category_ids[1] : null;
		$subCategory = Category::where('id', $second_object['id'])->first();
		
		$add_ons = json_decode($this->add_ons, true); 
		
		$add_ons = array_map('intval', $add_ons);
	
		// Now perform the query with the cleaned array
		$addons = AddOn::whereIn('id', $add_ons)->get();
		
		// Return the formatted array
		return [
			'id' => $this->id,
			'price' => $this->price,
			'discount' => $this->discount,
			'discount_type' => $this->discount_type,
			'product_type' => $this->product_type,

			// Add translations
			'translations' => $translations->map(function ($translation, $locale) {
				return [
					'name' => $translation['name'] ?? null,
					'description' => $translation['description'] ?? null,
					'locale' => $locale,
				];
			})->values(),

			// Other fields
			'tags' => json_decode($this->tags), // Assuming tags is a JSON encoded string
			'image' => env('APP_URL') . Storage::url('product/' . $this->image),
			'available_time_starts' => $this->available_time_starts,
			'available_time_ends' => $this->available_time_ends,
			'add_ons' => $addons, // Assuming addon_ids is an array

			// Variations field
			'options' => $this->variations ? collect(json_decode($this->variations))->map(function ($option) {
				return [
					'name' => $option->name,
					'type' => $option->type,
					'min' => $option->min,
					'max' => $option->max,
					'required' => $option->required,
					'values' => collect($option->values)->map(function ($value) {
						return [
							'label' => $value->label,
							'optionPrice' => $value->optionPrice,
						];
					}),
				];
			}) : [], 

			'category' => $category ? [
				'id' => $category->id,
				'name' => $category->name, 
			] : null, 
			
			'sub_category' => $subCategory ? [
				'id' => $subCategory->id,
				'name' => $subCategory->name, 
			] : null, 
		];
	}

}

