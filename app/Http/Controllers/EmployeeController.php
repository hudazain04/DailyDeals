<?php

namespace App\Http\Controllers;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ListEmployeeResource;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function create_employee(EmployeeRequest $request)
    {
        return auth()->user()->id;
        if ($user && $user->role == 'Merchant') {

            $rand_code = rand(1111,9999);

            $employee = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($rand_code),
                'phone_number' => $request->phone_number,
                'role' => 'Employee',
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = rand(111111,999999) . '.' . $image->getClientOriginalExtension();
                $path = public_path('/Image');
                $image->move($path,$image_name);

                $employee->image = $image_name;
                $employee->save();
            }
            
                Employee::create([
                    'user_id' => $employee->id,
                    'branch_id' => $request->branch_id,
                    'merchant_id' => $user->id,
                    'code' => Hash::make($rand_code),
                ]);
    
                $emp = new EmployeeResource($employee);
                $emp['code'] = $rand_code;

            return response()->json([
                'status' => 200,
                'message' => 'employee added successfully',
                'data' => $emp ,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    
    public function update_employee(EmployeeUpdateRequest $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {

            $employee = Employee::where('id',$request->employee_id)->first();
            $emp_user = User::where('id',$employee->user_id)->first();


            if ($employee->merchant_id ==  $user->id) {

                $emp_user->update([
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ]);

                $employee->update([
                    'branch_id' => $request->branch_id,
                ]);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image_name = rand(111111,999999) . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/Image');
                    $image->move($path,$image_name);
    
                    $emp_user->image = $image_name;
                    $emp_user->save();
                }
                
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Employee updated successfully',
                    'data' => new EmployeeResource($emp_user),
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

    public function create_new_code(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {

            $employee = Employee::where('id',$request->employee_id)->first();
            $emp_user = User::where('id',$employee->user_id)->first();
            $rand_code = rand(1111,9999);


            if ($employee->merchant_id ==  $user->id) {

                $emp_user->update([
                    'password' => Hash::make($rand_code),
                ]);

                $employee->update([
                    'code' => Hash::make($rand_code),
                ]);

                $emp = new EmployeeResource($emp_user);
                $emp['code'] = $rand_code;


                return response()->json([
                    'status' => 200,
                    'message' => 'Code updated successfully',
                    'data' => $emp,
                ]);

            } else {
                return response()->json([
                    'status' => 404,
                    'message' => ' not authirized',
                    'data' => null,
                ]);
            }
        }  else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function delete_employee(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->role == 'Merchant') {

            $employee = Employee::where('id',$request->employee_id)->first();
            $emp_user = User::where('id',$employee->user_id)->first();


            if ($employee->merchant_id ==  $user->id) {

                $employee->delete();
                $emp_user->delete();


                return response()->json([
                    'status' => 200,
                    'message' => 'deleted successfully',
                    'data' => null,
                ]);

            } else {
                return response()->json([
                    'status' => 404,
                    'message' => ' not authirized',
                    'data' => null,
                ]);
            }
        }  else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function get_employees_by_store(Request $request)
    {
        $user = auth()->user();
        $store = Store::find($request->store_id);
    
        if (!$store) {
            return response()->json([
                'status' => 404,
                'message' => 'Store not found',
                'data' => null,
            ]);
        }

        if($store->merchant_id == $user->id){
            $branches = $store->branches;
            $employees = collect();
            foreach ($branches as $branch) {
                $employees = $employees->merge($branch->employees);
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'List Employees',
                'data' => ListEmployeeResource::collection($employees),
            ]);
          } else {
            return response()->json([
                'status' => 403,
                'message' => 'not authorized',
                'data' => null,
            ]);
        }

        }

}
