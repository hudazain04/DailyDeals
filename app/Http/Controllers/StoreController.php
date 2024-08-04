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
                'type' => $request->type,
                'description' => $request->description,
                'visible' => $request->visible,
                'merchant_id' => $user->id,
            ]);
            
            return $this->success(new StoreResource($store),'store added successfully');
      
    }

    public function update_store(StoreRequest $request)
    {
            $user = auth()->user();
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id != $user->id) {
                return $this->error('not Authurized',403);
            } else {
                $store->fill($request->only([
                    'name', 
                    'type', 
                    'description', 
                    'visible', 
                    'merchant_id', 
                ]));
        
                $store->save();
    
                
                return $this->success(new StoreResource($store),'store updated successfully');
            }
    }

    public function delete_store(Request $request)
    {
            $user = auth()->user();
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id != $user->id) {
                return $this->error('not Authurized',403);

            } else {
                $store->delete();  
                return $this->success(null,'store deleted successfully');
            }  
    }

    public function list_visible_stores()
    {
        $visible_stores = Store::orderBy('verified', 'desc')->where('visible', 1)->get();
        return $this->success(StoreResource::collection($visible_stores),'visible stores');
    }

    public function list_merchant_stores()
    {
        $user = auth()->user();
        $merchant_stores = Store::where('merchant_id',$user->id)->get();
        return $this->success(StoreResource::collection($merchant_stores),'merchant stores');
    }

    public function list_all_stores()
    {
        $all_stores = Store::orderBy('verified', 'desc')->get();
        return $this->success(StoreResource::collection($all_stores),'all stores');
    }

    public function store_byID (Request $request)
    {
        $stores = Store::where('id',$request->id)->get();
        return $this->success(StoreResource::collection($stores),'list stores by id');
    }
}
