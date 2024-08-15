<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Http\Controllers\Controller;
use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use HttpResponse;
    
    public function add_store(StoreRequest $request)
    {
            $user = auth()->user();

            $store = Store::create([
                'name' => $request->name,
                'description' => $request->description,
                'visible' => $request->visible,
                'merchant_id' => $user->id,
            ]);
            
            return $this->success(new StoreResource($store),__('messages.StoreController.Store_Added_Successfully'));
      
    }

    public function update_store(StoreRequest $request)
    {
            $user = auth()->user();
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id != $user->id) {
                return $this->error(__('messages.StoreController.Not_Authurized'),403);
            } else {
                $store->fill($request->only([
                    'name', 
                    'description', 
                    'visible', 
                    'merchant_id', 
                ]));
        
                $store->save();
    
                
                return $this->success(new StoreResource($store),__('messages.StoreController.Store_Updated_Successfully'));
            }
    }

    public function delete_store(Request $request)
    {
            $user = auth()->user();
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id != $user->id) {
                return $this->error(__('messages.StoreController.Not_Authurized'),403);

            } else {
                $store->delete();  
                return $this->success(null,__('messages.StoreController.Store_Deleted_Successfully'));
            }  
    }

    public function list_visible_stores()
    {
        $visible_stores = Store::orderBy('verified', 'desc')->where('visible', 1)->get();
        return $this->success(StoreResource::collection($visible_stores),__('messages.StoreController.List_visible_Stores'));
    }

    public function list_merchant_stores()
    {
        $user = auth()->user();
        $merchant_stores = Store::where('merchant_id',$user->id)->get();
        return $this->success(StoreResource::collection($merchant_stores),__('messages.StoreController.List_Merchant_Stores'));
    }

    public function list_all_stores()
    {
        $all_stores = Store::orderBy('verified', 'desc')->get();
        return $this->success(StoreResource::collection($all_stores),__('messages.StoreController.List_All_Stores'));
    }

    public function store_byID (Request $request)
    {
        $stores = Store::where('merchant_id',$request->merchant_id )->get();
        return $this->success(StoreResource::collection($stores),__('messages.StoreController.list_stores'));
    }

    public function show_store (Request $request)
    {
        $store = Store::where('id',$request->store_id)->get();
        return $this->success(StoreResource::collection($store),__('messages.StoreController.Store_Details'));
    }

    
}



