<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_setting_id',
        'tax_type',
        'tax_rate',
        'status',
    ];

    public function branchSetting()
    {
        return $this->belongsTo(BranchSetting::class, 'branch_setting_id');
    }
}
