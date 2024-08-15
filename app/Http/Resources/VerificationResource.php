<?php

namespace App\Http\Resources;

use App\Models\Merchant;
use App\Models\Store;
use App\Models\User;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\b;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData=[
          'id'=>$this->id,
          'commercial_record'=>$this->commercial_record,
          'status'=>$this->status,
            'description'=>$this->description,
        ];
        if (Auth::user()->role === UserType::Admin)

        {
            $merchant=User::where('id',$this->merchant_id)->first();
            $fullName = $merchant->full_name;
        $store=Store::where('id',$this->store_id)->first();
        $baseData=array_merge($baseData,['merchant_id'=>$this->merchant_id,'merchant_name'=>$fullName,
            'store_id'=>$this->store_id,'store_name'=>$store->name]);
    }
   return $baseData;

    }
}
