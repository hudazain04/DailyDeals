<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\AddDiscountOfferRequest;
use App\Http\Requests\AddExtraOfferRequest;
use App\Http\Requests\AddGiftOfferRequest;
use App\Http\Requests\AddOfferTypeRequestRequest;
use App\Http\Requests\AddPercentageOfferRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\DiscountOfferResource;
use App\Http\Resources\ExtraOfferResource;
use App\Http\Resources\GiftOfferResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferTypeResource;
use App\Http\Resources\PercentageOfferResource;
use App\Http\Resources\ProductResource;
use App\HttpResponse\HttpResponse;
use App\Jobs\DeactivateOffer;
use App\Models\Comment;
use App\Models\Offer;
use App\Models\Offer_Branch;
use App\Models\Percentage_Offer;
use App\Models\Product;
use App\Models\Type_Of_Offer_Request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Types\OfferType;
use Illuminate\Support\Facades\DB;
use function Kreait\Firebase\RemoteConfig\defaultValue;

class OfferController extends Controller
{

    use HttpResponse;
    public function AddOfferTypeRequest(AddOfferTypeRequestRequest $request)
    {
        try {
            $user=$request->user();
            $offer_type=Type_Of_Offer_Request::create(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                    'user_id'=>$user->id,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),__('messages.offer_controller.create_offer_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateOfferTypeRequest($request_id,AddOfferTypeRequestRequest $request)
    {
        try {

            $offer_type=Type_Of_Offer_Request::find($request_id);
//            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->update(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),__('messages.offer_controller.update_offer_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function DeleteOfferTypeRequest($request_id)
    {
        try {
            $offer_type=Type_Of_Offer_Request::find($request_id);
//            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->delete();
            return $this->success(null,__('messages.offer_controller.delete_offer_request'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAll()
    {
        try {
            $offer_types=Type_Of_Offer_Request::all();
            return $this->success(OfferTypeResource::collection($offer_types),__('messages.successful_request'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllForUser(Request $request)
    {
        try {
            $user=$request->user();
            $offer_types=Type_Of_Offer_Request::where('user_id',$user->id)->get();
            return $this->success(OfferTypeResource::collection($offer_types),__('messages.successful_request'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function AddComment(AddCommentRequest $request)
    {
        try {
            $comment=Comment::create([
                'comment'=>$request->comment,
                'offer_id'=>$request->offer_id,
                'customer_id'=>Auth::user()->id,
            ]);
            return $this->success(CommentResource::make($comment),__('messages.offer_controller.create_comment'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function UpdateComment($comment_id,UpdateCommentRequest $request)
    {
        try {
            $comment=Comment::find($comment_id);
//            $comment=Comment::where('id',$comment_id)->first();
            if ($comment->customer_id != $request->user()->id)
            {
                return $this->error(__('messages.offer_controller.not_your_comment'),400);
            }
            else
            {
                $comment->update([
                    'comment'=>$request->comment,
                ]);
                return $this->success(CommentResource::make($comment),__('messages.offer_controller.update_comment'));
            }

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function DeleteComment($comment_id,Request $request)
    {
        try {
            $comment=Comment::find($comment_id);
//            $comment=Comment::where('id',$comment_id)->first();
            if ($comment->customer_id != $request->user()->id)
            {
                return $this->error(__('messages.offer_controller.not_your_comment'),400);
            }
            else
            {
                $comment->delete();
                return $this->success(null,__('messages.offer_controller.delete_comment'));
            }

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllCommentsOnOffer($offer_id)
    {
        try {
            $comments=Comment::where('offer_id',$offer_id)->get();
            return $this->success(CommentResource::collection($comments),__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function AddPercentageOffer(AddPercentageOfferRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $offer=Offer::create([
                'name'=>$request->name,
                'type'=>OfferType::Percentage,
                'image'=>$request->file('image'),
                'period'=>'2',
            ]);
            $offer->branches()->attach($request->branch_id);
            $offer->percentage_offer()->create([
                'percentage' => $request->percentage,
            ]);



            foreach($request->products as $product_id)
            {
                $offer->products()->attach($product_id);
            }
            $delay = Carbon::now()->addDays($request->period);
            DeactivateOffer::dispatch($offer)->delay($delay);
            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.create_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdatePercentageOffer(Request $request,$offer_id)
    {
        try
        {
            DB::beginTransaction();
            $offerData = $request->only(['name','image']);
            $offer=Offer::find($offer_id);
            $offer->update($offerData);
            $percentageData= $request->only(['percentage']);
            $offer->percentage_offer()->update($percentageData);
            if($request->products)
            {
                $offer->products()->sync($request->products);

//                foreach($request->products as $product_id)
//                {
//
//                    $offer->products()->delete();
//                    $offer->products()->attach($product_id);
//                }
            }

            DB::commit();
//            dd($offer);
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.update_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }

    public function AddDiscountOffer(AddDiscountOfferRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $offer=Offer::create([
                'name'=>$request->name,
                'type'=>OfferType::Discount,
                'image'=>$request->image,
                'period'=>'2',
            ]);
            $offer->branches()->attach($request->branch_id);
            $offer->discount_offer()->create(['discount'=>$request->discount]);
            foreach( $request->products as $product_id)
            {
                $offer->products()->attach($product_id);
            }
            $delay = Carbon::now()->addDays($request->period);
            DeactivateOffer::dispatch($offer)->delay($delay);
            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.create_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateDiscountOffer(Request $request,$offer_id)
    {
        try
        {
            DB::beginTransaction();
            $offerData = $request->only(['image', 'period','name']);
            $offer=Offer::find($offer_id);
            $offer->update($offerData);
            $discountData= $request->only(['discount']);
            $offer->discount_offer()->update($discountData);
            if ($request->products)
            {
                $offer->products()->sync($request->products);

            }

            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.update_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function AddGiftOffer(AddGiftOfferRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $offer=Offer::create([
                'name'=>$request->name,
                'type'=>OfferType::Gift,
                'image'=>$request->image,
                'period'=>'2',
            ]);
            $offer->branches()->attach($request->branch_id);
            $offer->gift_offer()->create(['product_id'=>$request->product_id]);
            foreach($request->products as $product_id)
            {
                $offer->products()->attach($product_id );
            }
            $delay = Carbon::now()->addDays($request->period);
            DeactivateOffer::dispatch($offer)->delay($delay);
            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.create_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateGiftOffer(Request $request,$offer_id)
    {
        try
        {
            DB::beginTransaction();
            $offerData = $request->only(['image','name']);
            $offer=Offer::find($offer_id);
            $offer->update($offerData);
            $giftData= $request->only(['product_id']);
            $offer->gift_offer()->update($giftData);
            if($request->products)
            {
                $offer->products()->sync($request->products);

//                foreach($request->products as $product_id)
//                {
//                    $offer->products()->delete();
//                    $offer->products()->attach($product_id);
//                }
            }

            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.update_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function AddExtraOffer(AddExtraOfferRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $offer=Offer::create([
//                'name'=>$request->name,
                'type'=>OfferType::Extra,
                'image'=>$request->image,
                'period'=>'2',
            ]);
            $offer->branches()->attach($request->branch_id);
            $offer->extra_offer()->create(['product_id'=>$request->product_id,
                'product_count'=>$request->product_count,
                'extra_count'=>$request->extra_count,
            ]);
            $delay = Carbon::now()->addDays($request->period);
            DeactivateOffer::dispatch($offer)->delay($delay);
            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.create_offer'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateExtraOffer(Request $request,$offer_id)
    {
        try
        {
            DB::beginTransaction();
            $offerData = $request->only(['image','name']);
            $offer=Offer::find($offer_id);
            $offer->update($offerData);
            $extraData= $request->only(['product_id','product_count','extra_count']);
            $offer->extra_offer()->update($extraData);
            DB::commit();
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.update_offer'));
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function DeleteOffer(Request $request,$offer_id)
    {
        try
        {
            $offer=Offer::find($offer_id);
            if (! $offer)
            {
                return $this->error(__('messages.offer_controller.not_found'),404);
            }
            $offer->delete();
            return $this->success(null,__('messages.offer_controller.delete_offer'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function DeactivateOffer($offer_id)
    {
        try
        {
            $offer=Offer::find($offer_id);
            if (! $offer)
            {
                return $this->error(__('messages.offer_controller.not_found'),404);
            }
            $offer->update(['active'=>false]);
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.archive_offer'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function ActivateOffer($offer_id)
    {
        try
        {
            $offer=Offer::find($offer_id);
            if (! $offer)
            {
                return $this->error(__('messages.offer_controller.not_found'),404);
            }
            $offer->update(['active'=>true]);
            $delay = Carbon::now()->addDays($offer->period);
            DeactivateOffer::dispatch($offer)->delay($delay);
            return $this->success(['offer'=>OfferResource::make($offer)],__('messages.offer_controller.activate_offer'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function GetOffers()
    {
        try
        {
            $offers = DB::table('offers')
                ->join('offer_branches', 'offers.id', '=', 'offer_branches.offer_id')
                ->join('branches', 'offer_branches.branch_id', '=', 'branches.id')
                ->join('stores', 'branches.store_id', '=', 'stores.id')
                ->select('offers.*')
                ->distinct()
                ->orderBy('stores.verified', 'desc')
                ->get();
//            return $offers;
            return $this->success(['offers'=>OfferResource::collection($offers)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetOffersOfBranch($branch_id)
    {
        try
        {
            $offers=Offer_Branch::where('branch_id',$branch_id)
            ->with('offer')->where('active',true)
            ->get()->pluck('offer');
//            return $offers;
            return $this->success(['offers'=>OfferResource::collection($offers)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetBranchArchive($branch_id)
    {
        try
        {
            $offers=Offer::where(['active'=>false])->get();
            return $this->success(['archived_offers'=>OfferResource::collection($offers)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);

        }
    }
    public function GetOffer($offer_id)
    {
        try
        {
           $offer=Offer::find($offer_id);
            if (! $offer)
            {
                return $this->error(__('messages.offer_controller.not_found'),404);
            }
           $offer_products=$offer->products();
           $products=[];
           foreach ($offer_products as $offer_product)
           {
               $product=Product::find($offer_product);
               array_push($products,$product);
           }
           return $this->success(['products'=>ProductResource::collection($products)],__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

}
