<?php

namespace App\Model;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Group extends Model
{
    protected $fillable = ['title'];

	public function table(){
		return $this->hasMany(Table::class);
	}
	
}