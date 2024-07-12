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
            $user=Auth::user();
            $verification_request=Verification::create(['commercial_record'=>AuthController::UploadImage($request),
                'merchant_id'=>$user->id,'store_id'=>$request->store_id,'status'=>RequestType::Pending]);
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],'verification requested successfully');

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
            $verification_request=Verification::where('id',$request_id)->first();
            $verification_request->update(['status'=>RequestType::Accepted]);
            $store=Store::where('id',$verification_request->store_id)->first();
            $store->update(['verified'=>true]);
            DB::commit();
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],
                'verification request accepted');

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
            $verification_request=Verification::where('id',$request_id)->first();
            $verification_request->update(['status'=>RequestType::Rejected]);
            $store=Store::where('id',$verification_request->store_id)->first();
            $store->update(['verified'=>false]);
            DB::commit();
            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],
                'verification request rejected');

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
            $verification_request=Verification::where('id',$request_id)->first();

            return $this->success(['verification_request'=>VerificationResource::make($verification_request)],'success');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAllForUser()
    {
        $user=Auth::user();
        try
        {
            $status=[RequestType::Pending,RequestType::Accepted,RequestType::Rejected];
            $verification_requests=Verification::where('merchant_id',$user->id)
                ->orderByRaw('FIELD(status,?,?,?)',$status)
                ->get();

            return $this->success(['verification_requests'=>VerificationResource::collection($verification_requests)],'success');


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
            return $this->success(['verification_requests'=>VerificationResource::collection($verification_requests)],'success');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }



}