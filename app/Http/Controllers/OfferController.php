<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOfferTypeRequestRequest;
use App\Http\Resources\OfferTypeResource;
use App\HttpResponse\HttpResponse;
use App\Models\Type_Of_Offer_Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{

    use HttpResponse;
    public function AddOfferTypeRequest(AddOfferTypeRequestRequest $request)
    {
        try {
            $user=Auth::user();
            $offer_type=Type_Of_Offer_Request::create(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                    'user_id'=>$user->id,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),'offer type requested successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateOfferTypeRequest($request_id,AddOfferTypeRequestRequest $request)
    {
        try {
            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->update(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),'offer type request updated successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function DeleteOfferTypeRequest($request_id)
    {
        try {
            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->delete();
            return $this->success(null,'offer type request deleted successfully');

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
            return $this->success(OfferTypeResource::collection($offer_types),'offer type requests');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllForUser()
    {
        try {
            $user=Auth::user();
            $offer_types=Type_Of_Offer_Request::where('user_id',$user->id)->get();
            return $this->success(OfferTypeResource::collection($offer_types),'offer type requests');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

}
