<?php

namespace App\Http\Helpers;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

trait TableGetterHelper
{
    public function filter_products($filters): void
    {
        $products = Product::filter($filters)->get();

        self::ok($products);
    }

    public function total_count($filters)
    {
        $products = Product::filter($filters)->latest()->get();

        return $products;
    }

    public function get_product_by_id($product_id): void
    {
        $product = Product::find($product_id);

        $product ? self::ok($product) : self::notFound();
    }

    public function get_user_orders(Request $request): void
    {
        if($request->user()->role == 'admin'){
            $orders = Order::latest()->get();
        }else{
            $orders = Order::byUser($request)->latest()->get();
        }

        self::ok($orders);
    }

    public function get_user_order_by_id($order_id,$user_id): void
    {
        $order = Order::where('id',$order_id)?->firstWhere('user_id',$user_id);

        $order ? self::ok($order) : self::notFound();
    }

    public function get_all_favorites_by_user($request): void
    {
        $favorites = Favorite::latest()->where('user_id',$request->user()->id)->get();

        self::ok($favorites);
    }

    public function get_user_favorite_by_id($product_id,$user_id): void
    {
        $favorite = Favorite::firstWhere('product_id',$product_id)?->where('user_id',$user_id);

        $favorite ? self::ok(true) : self::ok(false);
    }

    public function get_all_categories(): void
    {
        $categories = Category::withCount('category_products')->get();

        self::ok($categories);
    }
}
