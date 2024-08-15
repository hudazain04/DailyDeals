<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementRequest;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\ListAdvertisementResource;
use App\HttpResponse\HttpResponse;
use App\Models\Advertisement;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AdvertisementController extends Controller
{
    use HttpResponse;
    
    public function add_advertisement(AdvertisementRequest $request)
    {
        $user = auth()->user();
        $advertisement = Advertisement::create([
            'description' => $request->description,
            'phone_number' => $request->phone_number,
            'period' => $request->period,
            'user_id' => $user->id,
            'price' => null,
            'shown' => 0,
            'status' => 'Pending',
            'image' => $request->file('image'),
        ]);

        return $this->success(new AdvertisementResource($advertisement) ,__('messages.AdvertisementController.Advertisement_Added_Successfully'));

    }

    public function all_advertisement_requests()
    {
        $advertisements = Advertisement::where('status','Pending')->get();
        return $this->success( AdvertisementResource::collection($advertisements) ,__('messages.AdvertisementController.All_Advertisement_Requests'));
    }

    public function advertisement_details(Request $request)
    {
        $advertisement = Advertisement::where('id',$request->advertisement_id)->first();
        return $this->success(new AdvertisementResource($advertisement) ,__('messages.AdvertisementController.Advertisement_Details'));
    }

    public function accept_advertisement(Request $request)
    {
        $advertisement = Advertisement::where('id',$request->advertisement_id)->first();
        $advertisement->status = 'Accepted';
        $advertisement->shown = 1;
        $advertisement->price = $request->price;
        $advertisement->accepted_at = Carbon::now();
        $advertisement->save();

        return $this->success(new AdvertisementResource($advertisement) ,__('messages.AdvertisementController.Advertisement_Accepted'));
    }

    public function reject_advertisement(Request $request)
    {
        $advertisement = Advertisement::where('id',$request->advertisement_id)->first();
        $advertisement->status = 'Rejected';
        $advertisement->shown = 0;
        $advertisement->save();

        return $this->success(new AdvertisementResource($advertisement) ,__('messages.AdvertisementController.Advertisement_Rejected'));
    }

    public function list_advertisement()
    {
        $currentDate = Carbon::now();

        $advertisements = Advertisement::where('status','Accepted')->where('shown',1)
        ->where(function ($query) use ($currentDate) {
            $query->whereRaw("DATE_ADD(accepted_at, INTERVAL period DAY) >= ?", [$currentDate]);
        })
        ->get();
        return $this->success(ListAdvertisementResource::collection($advertisements) ,__('messages.AdvertisementController.List_Advertisements'));
    }

    public function advertisement_accepted_details(Request $request)
    {
        $advertisement = Advertisement::where('id',$request->advertisement_id)->where('status','Accepted')
        ->where('shown',1)->first();
        return $this->success(new ListAdvertisementResource($advertisement) ,__('messages.AdvertisementController.Advertisement_Details'));
    }
}
