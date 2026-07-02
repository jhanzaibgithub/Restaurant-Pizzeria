<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
	protected $fillable = ['kitchen_id','title','ip','branch_id','type','is_primary','status'];
	
	public function kitchen(){
	
		return $this->hasOne(Kitchen::class,'id','kitchen_id');
	}
}