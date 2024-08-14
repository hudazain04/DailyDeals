<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRateRequest;
use App\Http\Resources\QRResource;
use App\Http\Resources\RateResource;
use App\HttpResponse\HttpResponse;
use App\Jobs\CalculateBranchRate;
use App\Models\QR;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use HttpResponse;


    public function AddRate(AddRateRequest $request)
    {
        try {
            $rate=Rate::create([
                'rate'=>$request->rate,
                'customer_id'=>$request->user()->id,
                'branch_id'=>$request->branch_id,
            ]);
//            dd($rate);
            CalculateBranchRate::dispatchAfterResponse($request->branch_id);

            return $this->success(RateResource::make($rate),__('messages.rate_controller.create_rate'));

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetBranchRates($branch_id)
    {
        try {
            $rates=Rate::where('branch_id',$branch_id)->get();
            return $this->success(RateResource::collection($rates),__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetBranchQRs($branch_id)
    {
        try {
            $qrs=QR::where('branch_id',$branch_id)->get();
            return $this->success(QRResource::collection($qrs),__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
}
