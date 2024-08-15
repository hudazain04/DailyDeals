<?php

namespace App\Http\Controllers;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ListEmployeeResource;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Controllers\Controller;
use App\HttpResponse\HttpResponse;
use App\Models\Employee;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    use HttpResponse;
    
    public function generateUniqueCode() {
        do {
            $rand_code = Str::random(4);
            $exists = Employee::where('code', $rand_code)->exists();
        } while ($exists);
        
        return $rand_code;
    }  

    public function create_employee(EmployeeRequest $request)
    {
        $rand_password = Str::random(8);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($rand_password),
            'phone_number' => $request->phone_number,
            'image' => $request->file('image'),
            'role' => 'Employee',
        ]);
        
        $employee = Employee::create([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'merchant_id' => auth()->user()->id,
                'code' => $this->generateUniqueCode(),
            ]);
     
            $results = new EmployeeResource($user);
            $results['code'] = $employee->code;
            $results['password_text'] = $rand_password;


        return $this->success($results ,__('messages.EmployeeController.Employee_Added_Successfully'));

    }
    
    public function update_employee(EmployeeUpdateRequest $request)
    {

            $employee = Employee::where('id',$request->employee_id)->first();
            $user = User::where('id',$employee->user_id)->first();


            if ($employee->merchant_id != auth()->user()->id) {
                return $this->error(__('messages.EmployeeController.You_Are_Not_Authorized'),403);
            } else {

                $user->fill($request->only([
                    'email', 
                    'phone_number', 
                ]));
        
                $user->save();

                $employee->fill($request->only([
                    'branch_id', 
                ]));
        
                $employee->save();

                if ($request->hasFile('image')) {
                    $user->image = $request->file('image');
                    $user->save();
                }
                
                return $this->success(new EmployeeResource($user) ,__('messages.EmployeeController.Employee_Updated_Successfully'));
            }
    }
    public function create_new_code(Request $request)
    {

            $employee = Employee::where('id',$request->employee_id)->first();
            $user = User::where('id',$employee->user_id)->first();
            $rand_password = Str::random(8);

            if ($employee->merchant_id !=  auth()->user()->id) {
                return $this->error(__('messages.EmployeeController.You_Are_Not_Authorized'),403);
            } else {
                $employee->update([
                    'code' => $this->generateUniqueCode(),
                ]);
    
                $user->update([
                    'password' => Hash::make($rand_password),
                ]);

                $results = new EmployeeResource($user);
                $results['code'] = $employee->code;
                $results['password_text'] = $rand_password;

                return $this->success($results ,__('messages.EmployeeController.Code_Updated_Successfully'));
            }
    }

    public function delete_employee(Request $request)
    {
            $employee = Employee::where('id',$request->employee_id)->first();
            $user = User::where('id',$employee->user_id)->first();

            if ($employee->merchant_id !=  auth()->user()->id) {
                return $this->error(__('messages.EmployeeController.You_Are_Not_Authorized'),403);
            } else {
                $employee->delete();
                $user->delete();
                return $this->success(null ,__('messages.EmployeeController.Employee_Deleted_Successfully'));
            }
    }


    public function get_employees_by_store(Request $request)
    {
        $store = Store::find($request->store_id);
    
        if (!$store) {
          return $this->error(__('messages.EmployeeController.Store_Not_Found'),404);
        } elseif($store->merchant_id != auth()->user()->id) {
            return $this->error(__('messages.EmployeeController.You_Are_Not_Authorized'),403);
        } else {
            $branches = $store->branches;
            $employees = collect();
            foreach ($branches as $branch) {
                $employees = $employees->merge($branch->employees);
            }
            return $this->success(ListEmployeeResource::collection($employees),__('messages.EmployeeController.List_Employees'));
        }

    }

}
