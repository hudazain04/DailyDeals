<?php

namespace App\Http\Controllers;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\NumberResource;
use App\Models\Branch;
use App\Models\Number;
use App\Models\Employee;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{

    public function create_branch(BranchRequest $request)
    {
        $user = auth()->user();
        if ($user && $user->role == 'Merchant') {

            $branch = Branch::create([
                'name' => $request->name,
                'location' => $request->location,
                'google_maps' => $request->google_maps,
                'store_id' => $request->store_id,
                'category_id' => $request->category_id,
                'visible' => $request->visible,
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = rand(111111,999999) . '.' . $image->getClientOriginalExtension();
                $path = public_path('/Image');
                $image->move($path,$image_name);

                $branch->image = $image_name;
                $branch->save();
            }

            foreach ($request->phone_numbers as $phone_number) {
                Number::create([
                    'phone_number' => $phone_number,
                    'branch_id' => $branch->id,
                ]);
            }

        

            return response()->json([
                'status' => 200,
                'message' => 'branch added successfully',
                'data' => new BranchResource($branch),
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }



    public function update_branch(BranchRequest $request)
    {
        $user = auth()->user();

        if ($user && ($user->role == 'Merchant' || $user->role == 'Employee')) {
            $branch = Branch::where('id',$request->branch_id)->first();

            $store = Store::where('id',$branch->store_id)->first();
            $employee = Employee::where('user_id',$user->id)->first();
            $emp_branch = null;

            if ( $employee) {
                $emp_branch = Branch::where('id',$employee->branch_id)
                ->where('id',$request->branch_id)->first();
            }


            if ($store->merchant_id == $user->id || $emp_branch != null) {

                $branch->update([
                    'name' => $request->name,
                    'location' => $request->location,
                    'google_maps' => $request->google_maps,
                    'store_id' => $request->store_id,
                    'category_id' => $request->category_id,
                    'visible' => $request->visible,
                ]);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image_name = rand(111111,999999) . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/Image');
                    $image->move($path,$image_name);
    
                    $branch->image = $image_name;
                    $branch->save();
                }

                $branch->numbers()->delete();

                foreach ($request->phone_numbers as $phone_number) {
                    Number::create([
                        'phone_number' => $phone_number,
                        'branch_id' => $branch->id,
                    ]);
                }
    
                
                
                return response()->json([
                    'status' => 200,
                    'message' => 'branch updated successfully',
                    'data' => new BranchResource($branch),
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => ' not authirized',
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


    public function delete_branch(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {
            $branch = Branch::where('id',$request->branch_id)->first();
            $store = Store::where('id',$branch->store_id)->first();

            if ($store->merchant_id == $user->id) {
                $branch->delete(); 
                return response()->json([
                    'status' => 200,
                    'message' => 'branch deleted successfully',
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

    public function list_merchant_branches(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {

            $store = Store::where('id',$request->store_id)->first();

            if ($store->merchant_id == $user->id) {
                $branches = Branch::where('store_id',$request->store_id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'list branches',
                'data' => BranchResource::collection($branches),
            ]);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'this is not your store',
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

    public function list_employee_branches()
    {
        $user = auth()->user();

        if ($user && $user->role == 'Employee') {

            $employee = Employee::where('user_id',$user->id)->first();


            $branches = Branch::where('id',$employee->branch_id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'list branches',
                'data' => BranchResource::collection($branches),
            ]);


        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }


    public function list_customer_branches(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Customer') {

            $branches = Branch::where('visible',1)->where('store_id',$request->store_id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'list branches',
                'data' => BranchResource::collection($branches),
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function list_admin_branches(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Admin') {

            $branches = Branch::where('store_id',$request->store_id)->get();
    
            return response()->json([
                'status' => 200,
                'message' => 'list branches',
                'data' => BranchResource::collection($branches),
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function Branch_byID (Request $request)
    {
        $branches = Branch::where('id',$request->id)->get();

        return response()->json([
            'status' => 200,
            'message' => 'branches',
            'data' => BranchResource::collection($branches),
        ]);
    }

}
