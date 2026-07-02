<?php

namespace App\Model;

use App\Model\Order;
use App\Model\Table;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableReservation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'table_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_id',
        'branch_table_token',
        'branch_table_token_is_expired',
        'user_id',
        'order_id',
        'date_time',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'branch_table_token_is_expired' => 'boolean',
        'date_time' => 'datetime',
    ];

    /**
     * Get the table associated with the reservation.
     */
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    /**
     * Get the user who made the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the associated order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
