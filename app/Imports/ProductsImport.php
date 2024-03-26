<?php

namespace App\Imports;

use App\Models\CategoryProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Mockery\Expectation;

class ProductsImport implements ToModel, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = Product::create([
            'scientific_name' => $row[0],
            'commercial_name' => $row[1],
            'company_name' => $row[2],
            'description' => $row[3],
            'price' => $row[4],
            'quantity' => $row[5],
            'is_quantity' => $row[6],
            'expiration' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]),
            // 'image' => !$row[9] ?'/images/noImage.jpg' : ''
        ]);

        foreach(json_decode($row[8]) as $category_id){
            CategoryProduct::create([
                'category_id' => $category_id,
                'product_id' => $product->id
            ]);
        }

        try{
            if ($row[9]) {
                $image = $row[9];
                $imageName = time().'_'.$image->getBasename().'.'.$image->getClientOriginalExtension();
                copy($image, public_path('images/' . $imageName));
                $product->image = '/images/' .  $imageName;
                $product->save();
            }
        }catch(Expectation $e){
            $product->image = '/images/noImage.jpg';
            $product->save();
        }
    

        return $product;
    }
}
