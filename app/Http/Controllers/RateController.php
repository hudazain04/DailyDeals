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
            CalculateBranchRate::dispatch($request->branch_id);

            return $this->success(RateResource::make($rate),'rate added successfully');

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
            return $this->success(RateResource::collection($rates),'branch rates');
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
            return $this->success(QRResource::collection($qrs),'branch qrs');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
}
