<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function add_store(StoreRequest $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {
            $store = Store::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'visible' => $request->visible,
                'merchant_id' => $user->id,
            ]);
            
            return response()->json([
                'status' => 200,
                'message' => 'store added successfully',
                'data' => new StoreResource($store),
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function update_store(StoreRequest $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id == $user->id) {
                $store->update([
                    'name' => $request->name,
                    'type' => $request->type,
                    'description' => $request->description,
                    'visible' => $request->visible,
                    'merchant_id' => $user->id,
                ]);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'store updated successfully',
                    'data' => new StoreResource($store),
                ]);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'not Authurized',
                    'data' => null,
                ]);
            }


        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function delete_store(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {
            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id == $user->id) {
                $store->delete();  
                
                return response()->json([
                    'status' => 200,
                    'message' => 'store deleted successfully',
                    'data' => null,
                ]);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'not Authurized',
                    'data' => null,
                ]);
            }  

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function list_visible_stores()
    {
        $visible_stores = Store::where('visible',1)->get();

        return response()->json([
            'status' => 200,
            'message' => 'visible stores',
            'data' => StoreResource::collection($visible_stores),
        ]);
    }

    public function list_merchant_stores()
    {
        $user = auth()->user();
        if ($user && $user->role == 'Merchant') {
            $merchant_stores = Store::where('merchant_id',$user->id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'merchant stores',
                'data' => StoreResource::collection($merchant_stores),
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    
    }

    public function list_all_stores()
    {
        $all_stores = Store::get();

        return response()->json([
            'status' => 200,
            'message' => 'all stores',
            'data' => StoreResource::collection($all_stores),
        ]);
    }

    public function store_byID (Request $request)
    {
        $merchant_stores = Store::where('merchant_id',$request->merchant_id)->get();

        return response()->json([
            'status' => 200,
            'message' => 'merchant stores',
            'data' => StoreResource::collection($merchant_stores),
        ]);
    }
}
