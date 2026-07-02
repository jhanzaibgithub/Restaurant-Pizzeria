<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Printer;

class Kitchen extends Model
{
	
	protected $fillable = ['title', 'image','branch_id'];

	public function printers(){
	
		return $this->hasOne(Printer::class);
	}
	
	public function printer(){
	
		return $this->hasMany(Printer::class);
	}
	
   
}
