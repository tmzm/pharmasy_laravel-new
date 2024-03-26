<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static byOwnerAndProductId($product_id, $id)
 * @method static filter($filters)
 */
class Product extends Model
{
    use HasFactory;

    protected $with = ['category_products'];

    protected $guarded = [];

    /**
     * @param $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters){

        if($filters['search'] ?? false){

            $query->where(
                fn($query)=>
                $query
                    ->where('scientific_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('commercial_name', 'like', '%' . $filters['search'] . '%')
            );

        }

        if($filters['categories'] ?? false){

            $query->whereHas('category_products', fn ($query)

            => $query->whereIn('category_id', $filters['categories']));

        }

        if($filters['take'] ?? false){

            $query->take($filters['take']);

        }

        if($filters['skip'] ?? false){

            $query->skip($filters['skip']);

        }

        if($filters['price'] ?? false){

            $query->whereBetween('price',$filters['price']);

        }

    }

    public function category_products()
    {
        return $this->hasMany(CategoryProduct::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
