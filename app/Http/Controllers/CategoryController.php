<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        self::get_all_categories();
    }

    public function create(Request $request)
    {
        return self::create_category($request);
    }

    public function edit(Request $request,$category_id)
    {
        return self::edit_category($request,$category_id);
    }

    public function destroy($category_id)
    {
        return self::destroy_category($category_id);
    }
}
