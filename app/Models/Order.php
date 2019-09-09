<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['status'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_order')->withPivot('id', 'count')->withTimestamps();
    }
}
