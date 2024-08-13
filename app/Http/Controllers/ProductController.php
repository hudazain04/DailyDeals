<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddColorsRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AddSizesRequest;
use App\Http\Resources\ColorResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SizeResource;
use App\HttpResponse\HttpResponse;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Product_Info;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use HttpResponse;

    public function AddProduct(AddProductRequest $request)
    {
        try
        {
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'visible' => true,
                'store_id' => $request->store_id,
            ]);
            return $this->success(['product'=>ProductResource::make($product)],'created product');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AddColors(AddColorsRequest $request,$product_id)
    {
        try
        {
            DB::beginTransaction();
            $colors=$request->colors;
            foreach ($colors as $color)
            {
                $color1=Color::find($color['color']);
                $image=Image::create([
                    'color_id'=>$color1->id,
                    'product_id'=>$product_id,
                    'image'=>$color->file('image'),
                ]);
            }
            DB::commit();
            return $this->success(['colors'=>ColorResource::collection($colors)],'colors added');


        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function AddSizes(AddSizesRequest $request,$product_id)
    {
        try
        {
            DB::beginTransaction();
            $sizes=$request->sizes;
            foreach ($sizes as $size)
            {
                $size1=Size::create([
                    'size'=>$size['size'],
                    'unit'=>$size['unit'],
                    'price'=>$size['price'],
                ]);
                foreach ($size['colors'] as $color)
                {
                    $product_info=Product_Info::create([
                        'product_id'=>$product_id,
                        'size_id'=>$size1->id,
                        'color_id'=> $color,
                ]);

                }

            }
            DB::commit();
            return $this->success(['sizes'=>SizeResource::collection($sizes)->additional(['product_id' => $product_id])],'sizes created');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }

    public function DeleteProduct($product_id)
    {
        try
        {
            $product=Product::find($product_id);
            $product->delete();
            return $this->success(null,'deleted product');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetProduct($product_id)
    {
        try
        {
            $product=Product::find($product_id);
            return $this->success(['product'=>ProductResource::make($product)],'product');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetStoreProducts($store_id)
    {
        try
        {
         $products=Product::where('store_id',$store_id)->get();
         return $this->success(['products'=>ProductResource::collection($products)],'products');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

}
