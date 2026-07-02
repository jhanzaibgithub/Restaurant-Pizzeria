<?php

// app/Models/Setting.php
namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_name', 'phone', 'email', 'address', 'country', 'time_zone', 'time_format',
        'currency', 'currency_position', 'digit_after_decimal', 'copyright_text', 'pagination',
        'min_order_value', 'food_preparation_time', 'schedule_order_slot_duration',
        'latitude', 'longitude', 'coverage_km', 'self_pickup', 'delivery', 'email_verification',
        'phone_verification', 'deliveryman_self_registration', 'veg_non_veg_option', 'status',
        'fav_icon', 'banner_image'
    ];

    protected $casts = [
        'tax_details' => 'array',
        'self_pickup' => 'boolean',
        'delivery' => 'boolean',
        'email_verification' => 'boolean',
        'phone_verification' => 'boolean',
        'deliveryman_self_registration' => 'boolean',
        'veg_non_veg_option' => 'boolean',
        'status' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function taxes()
{
    return $this->hasMany(BranchTax::class, 'branch_setting_id');
}
}

