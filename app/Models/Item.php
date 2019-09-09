<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = ['name'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'item_order')->withPivot('id','count')->withTimestamps();
    }
}
