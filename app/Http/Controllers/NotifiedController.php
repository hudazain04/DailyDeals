<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotifiedRequest;
use App\Http\Resources\NotifiedResource;
use App\HttpResponse\HttpResponse;
use App\Models\Notified;
use Illuminate\Http\Request;


class NotifiedController extends Controller
{
    use HttpResponse;
    
    public function list_my_notified()
    {
        $user = auth()->user();
        $notifieds = Notified::where('customer_id' , $user->id)->get();
        return $this->success(NotifiedResource::collection($notifieds) ,__('messages.NotifiedController.List_My_Notifieds'));
    }

    
    public function add_notified(NotifiedRequest $request)
    {
        $user = auth()->user();
        
        $notified = Notified::create([
            'customer_id' => $user->id,
            'branch_id' => $request->branch_id,
        ]);

        return $this->success(new NotifiedResource($notified) ,__('messages.NotifiedController.Notified_Added_Successfully'));
        }


            public function delete_notified(Request $request)
            {
            $user = auth()->user();
            $notified = Notified::where('customer_id',$user->id)->where('branch_id',$request->branch_id)->first();
            $notified->delete();
            return $this->success(null ,__('messages.NotifiedController.Notified_Deleted_Successfully'));
        }
        

}