<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $table = 'tables';

    protected $fillable = ['group_id', 'number', 'capacity','is_available','branch_id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id', 'id');
    }

    public function table_order(): HasMany
    {
        return $this->hasMany(TableOrder::class, 'table_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
