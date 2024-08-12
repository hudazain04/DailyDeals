<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\HttpResponse\HttpResponse;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponse;

    public function addProduct(AddProductRequest $request)
    {

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'visible' => true,
            'store_id' => $request->store_id,
        ]);


        // Add colors and their specific images
        foreach ($request->colors as $colorData) {
            $color = $product->colors()->create(['color' => $colorData['color']]);

            // Store the image associated with the color
            $product->images()->create([
                'image' => $colorData['image'], // Handle file upload if necessary
                'color_id' => $color->id,
            ]);
        }

        // Add sizes
        foreach ($request->sizes as $size) {
            $product->sizes()->create(['size' => $size['size'], 'unit' => $size['unit']]);
        }

        $info=$product->productInfo()->create([
            'price' => $request->price,
            'size_id' => $request->product_info['size_id'],
            'color_id' => $request->product_info['color_id'],
        ]);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);

    }
}
