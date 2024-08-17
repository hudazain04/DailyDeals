<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddColorsRequest;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AddSizesRequest;
use App\Http\Resources\ColorResource;
use App\Http\Resources\ProductPriceResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SizeResource;
use App\HttpResponse\HttpResponse;
use App\Models\Color;
use App\Models\Image;
use App\Models\Offer;
use App\Models\Offer_Branch;
use App\Models\Product;
use App\Models\Product_Info;
use App\Models\Size;
use App\Types\OfferType;
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
                'visible' => $request->visible,
                'store_id' => $request->store_id,
            ]);
            $colors=Color::all();
            return $this->success(['product'=>ProductResource::make($product)->additional(['size'=>null]),'colors'=>$colors],__('messages.product_controller.create_product'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AddColors(AddColorsRequest $request)
    {
        try
        {

            DB::beginTransaction();
            $colors=$request->colors;
            foreach ($colors as $index => $color)
            {
                $color1=Color::find($color['color']);
//                dd($color1);
                $image=Image::create([
                'color_id'=>$color1->id,
                    'product_id'=>$request->product_id,
                    'image'=>$request->file('colors.' . $index . '.image'),
                ]);

            }
            DB::commit();
            $colorResources = collect($colors)->map(function ($color) use ($request) {
                $colorModel = Color::find($color['color']); // Fetch the color model
                return new ColorResource($colorModel, $request->product_id);
            });

            return $this->success([
                'colors' => $colorResources
            ], __('messages.product_controller.create_colors'));
//            return $this->success(['colors'=>ColorResource::collection($colors)
//                    ->additional(['product_id'=>$request->product_id])]
//                    ,__('messages.product_controller.create_colors'));

        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function AddSizes(AddSizesRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $sizes=$request->sizes;
            $s=[];
            foreach ($sizes as $size)
            {
                $size1=Size::create([
                    'size'=>$size['size'],
                    'unit'=>$size['unit'],
                    'price'=>$size['price'],
                ]);
                $s[] = $size1;
                foreach ($size['colors'] as $color)
                {
                    $product_info=Product_Info::create([
                        'product_id'=>$request->product_id,
                        'size_id'=>$size1->id,
                        'color_id'=> $color,
                ]);

                }

            }
            DB::commit();
//            dd($createdsizes);
//            return $this->success(['sizes'=>SizeResource::collection($sizes)
//                ->additional(['product_id' => $request->product_id])]
//                ,__('messages.product_controller.create_sizes'));
            $sizeResources = collect($s)->map(function ($size) use ($request) {
//                $sizeModel = Size::find($size['size']);
                return new SizeResource($size, $request->product_id);
            });
//            dd($sizeResources);

            return $this->success([
                'sizes' => $sizeResources,
            ], __('messages.product_controller.create_sizes'));
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
            if (!$product)
            {
                return $this->error(__('messages.product_controller.not_found'),404);
            }
            $product->delete();
            return $this->success(null,__('messages.product_controller.delete_product'));
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
            if(! $product)
            {
                return $this->error(__('messages.product_controller.not_found'),404);
            }
            return $this->success(['product'=>ProductResource::make($product)],__('messages.successful_request'));
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
         return $this->success(['products'=>ProductResource::collection($products)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetRecentProducts($store_id)
    {
        try
        {
            $products=Product::where('store_id',$store_id)
//            ->latest(5)
//            ->take(5)
            ->get();
            return $this->success(['products'=>ProductResource::collection($products)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetColors()
    {
        try
        {
            $colors=Color::all();
            return $this->success(['colors'=>ColorResource::collection($colors)],__('mesaages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetOfferProducts($offer_id)
    {
        try
        {
            $offer=Offer::find($offer_id);
            $products=$offer->products()->get();
            if ($offer->type==OfferType::Percentage || $offer->type==OfferType::Discount)
            {
                foreach ($products as $product)
                {
                    $product->setRelation('offer',$offer);
                }
                return $this->success(['products'=>ProductPriceResource::collection($products)],__('messages.successful_request'));
            }
            return $this->success(['products'=>ProductResource::collection($products)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

}
