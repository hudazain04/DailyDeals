<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddVerificationRequestRequest;
use App\Http\Resources\VerificationResource;
use App\HttpResponse\HttpResponse;
use App\Models\Store;
use App\Models\User;
use App\Models\Verification;
use App\Types\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class VerificationController extends Controller
{
    use HttpResponse;
    public function AddVerificationRequest(AddVerificationRequestRequest $request)
    {
        try {
            $user=$request->user();
            $verification_request=Verification::create(['commercial_record'=>file('image'),
                'merchant_id'=>$user->id,'store_id'=>$request->store_id,'status'=>RequestType::Pending]);
            if($request->description)
            {
                $verification_request->update(['description'=>$request->description]);
            }
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],__('messages.verification_controller.create_verification'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AcceptVerificationRequest($request_id)
    {
        try {
            DB::beginTransaction();

            $verification_request=Verification::find($request_id);
//            $verification_request=Verification::where('id',$request_id)->first();
            $verification_request->update(['status'=>RequestType::Accepted]);
            $store=Store::find($verification_request->store_id);
            $store->update(['verified'=>true]);
            DB::commit();
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],
                __('messages.verification_controller.accept_verification'));

        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function RejectVerificationRequest($request_id)
    {
        try {
            DB::beginTransaction();
            $verification_request=Verification::find($request_id);
//            $verification_request=Verification::where('id',$request_id)->first();
            $verification_request->update(['status'=>RequestType::Rejected]);
            $store=Store::find($verification_request->store_id);
//            $store=Store::where('id',$verification_request->store_id)->first();
            $store->update(['verified'=>false]);
            DB::commit();
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],
                __('messages.verification_controller.reject_verification'));

        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetVerificationRequest($request_id)
    {
        try
        {
            $verification_request=Verification::find($request_id);
//            $verification_request=Verification::where('id',$request_id)->first();

            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],__('messages.successful_request'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAllForUser(Request $request)
    {
        $user=$request->user();
        try
        {
            $status=[RequestType::Pending,RequestType::Accepted,RequestType::Rejected];
            $verification_requests=Verification::where('merchant_id',$user->id)
                ->orderByRaw('FIELD(status,?,?,?)',$status)
                ->get();

            return $this->success(['verification_requests'=>VerificationResource::collection($verification_requests)],__('messages.successful_request'));


        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAll()
    {
        try {
            $status=[RequestType::Pending,RequestType::Accepted,RequestType::Rejected];
            $verification_requests=Verification::orderByRaw('FIELD(status,?,?,?)',$status)->get();
            return $this->success(['verification_requests'=>VerificationResource::collection($verification_requests)],__('messages.successful_request'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }



}
