<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\CategoryProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Mockery\Expectation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Generate a unique id
        $uniqueId = Str::uuid()->toString();
        // Clean the name and create slug
        $slug = Str::slug($row['sname']);
        // Check if slug already exists
        $count = Product::where('slug', $slug)->count();
        // If slug already exists, append a number to make it unique
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        // Append the unique id to the slug
        $slug .= '-' . $uniqueId;

        $product = Product::create([
            'scientific_name' => $row['sname'],
            'commercial_name' => $row['cname'],
            'company_name' => $row['coname'],
            // 'description' => $row[3],
            'description' => 'no description',
            'price' => $row['price'],
            'quantity' => $row['quantity'] == 'always' ? 0 : $row['quantity'],
            'is_quantity' => $row['quantity'] == 'always' ? 0 : 1,
            'offer' => $row['offer'] == 'no' ? null : $row['offer'],
            'is_offer' => $row['offer'] == 'no' ? 0 : 1,
            // 'expiration' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7]),
            'expiration' => now(),
            // 'image' => !$row[9] ?'/images/noImage.jpg' : ''
            'slug' => $slug,
            'meta_subtitle' => 'subtitle-subtitle',
            'meta_title' => 'title-title-title',
            'meta_description' => 'description-description-description'
        ]);

        foreach(json_decode($row['category']) as $category_id){
            CategoryProduct::create([
                'category_id' => $category_id,
                'product_id' => $product->id
            ]);
        }

        // try{
        //     if ($row['image']) {
        //         $image = $row[9];
        //         $imageName = time().'_'.$image->getBasename().'.'.$image->getClientOriginalExtension();
        //         copy($image, public_path('images/' . $imageName));
        //         $product->image = '/images/' .  $imageName;
        //         $product->save();
        //     }
        // }catch(Expectation $e){
        //     $product->image = '/images/noImage.jpg';
        //     $product->save();
        // }
    

        return $product;
    }
}
