<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static byOrderItemId($id)
 * @method static byProduct($id)
 * @method static byUser(Request $request)
 */
class Order extends Model
{
    use HasFactory;

    public function scopeByUser($query,Request $request)
    {
        $query->where('user_id',$request->user()->id);
    }

    public function scopeByOrderItemId($query,$order_item_id)
    {
       $query->whereHas('order_items',fn ($query) =>
            $query->where('id',$order_item_id)
        );
    }

    public function scopeByProduct($query,$product_id)
    {
        $query->whereHas('order_items',fn($query)=>
        $query->whereHas('product',fn($query)=>
        $query->where('id',$product_id)
        )
        );
    }

    protected $guarded = [];

    protected $with = ['user','order_items','location'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
