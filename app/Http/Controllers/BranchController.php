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
use App\HttpResponse\HttpResponse;
use App\Models\QR;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use HttpResponse;

    public function create_branch(BranchRequest $request)
    {
            $branch = Branch::create([
                'name' => $request->name,
                'location' => $request->location,
                'google_maps' => $request->google_maps,
                'store_id' => $request->store_id,
                'category_id' => $request->category_id,
                'visible' => $request->visible,
                'image' => $request->file('image'),
            ]);

            foreach ($request->phone_numbers as $phone_number) {
                Number::create([
                    'phone_number' => $phone_number,
                    'branch_id' => $branch->id,
                ]);
            }

            for ($i=1; $i <= 5; $i++) {
                QR::create([
                    'rate' => $i,
                    'branch_id' => $branch->id,
                    'image' =>  $request->file('rate' . $i),
                ]);
            }

            return $this->success(new BranchResource($branch) ,'branch added successfully');
    }



    public function update_branch(BranchRequest $request)
    {
        if (auth()->user()->role != 'Merchant' && auth()->user()->role != 'Employee') {
            return $this->error('not authorized',403);

            } else {
                $branch = Branch::where('id',$request->branch_id)->first();
                $store = Store::where('id',$branch->store_id)->first();
                $employee = Employee::where('user_id',auth()->user()->id)->first();
                $emp_branch = null;

                if ($employee) {
                    $emp_branch = Branch::where('id',$employee->branch_id)
                    ->where('id',$request->branch_id)->first();
                }

                if ($store->merchant_id == auth()->user()->id || $emp_branch != null) {


                    $data = $request->only([
                        'name',
                        'location',
                        'google_maps',
                        'store_id',
                        'category_id',
                        'visible',
                    ]);

                    if ($request->hasFile('image')) {
                        $data['image'] = $request->file('image');
                    }

                    $branch->fill($data);
                    $branch->save();


                    $existingNumbers = $branch->numbers;
                    $newPhoneNumbers = $request->phone_numbers;

                    foreach ($existingNumbers as $key => $number) {
                        if (isset($newPhoneNumbers[$key])) {
                            $number->update([
                                'phone_number' => $newPhoneNumbers[$key],
                            ]);
                        }
                    }


                    return $this->success(new BranchResource($branch) ,'branch updated successfully');
            }
        }
    }


    public function delete_branch(Request $request)
    {
            $branch = Branch::where('id',$request->branch_id)->first();
            $store = Store::where('id',$branch->store_id)->first();

            if ($store->merchant_id != auth()->user()->id) {
                return $this->error('not authorized',403);
            } else {
                $branch->delete();
                return $this->success(null ,'branch deleted successfully');
            }
    }


    public function list_merchant_branches(Request $request)
    {
            $store = Store::where('id',$request->store_id)->first();
            if ($store->merchant_id != auth()->user()->id) {
                return $this->error('not authorized',403);
            } else {
                $branches = Branch::where('store_id',$request->store_id)->get();
                return $this->success(BranchResource::collection($branches) ,'list branches');
            }
    }


    public function list_employee_branches()
    {
            $employee = Employee::where('user_id',auth()->user()->id)->first();
            $branches = Branch::where('id',$employee->branch_id)->get();
            return $this->success(BranchResource::collection($branches) ,'list branches');
    }


    public function list_customer_branches(Request $request)
    {
            $branches = Branch::where('visible',1)->where('store_id',$request->store_id)->get();
            return $this->success(BranchResource::collection($branches) ,'list branches');
    }

    public function list_admin_branches(Request $request)
    {
            $branches = Branch::where('store_id',$request->store_id)->get();
            return $this->success(BranchResource::collection($branches) ,'list branches');
    }

    public function Branch_byID (Request $request)
    {
        $branches = Branch::where('id',$request->id)->get();
        return $this->success(BranchResource::collection($branches) ,'list branches');
    }

}
