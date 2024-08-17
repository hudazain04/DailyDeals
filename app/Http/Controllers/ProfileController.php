<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use HttpResponse;
    
    public function get_my_profile()
    {
        $user = auth()->user();
        return $this->success(new ProfileResource($user) ,__('messages.ProfileController.My_Profile'));
    }


    public function update_my_profile(profileRequest $request)
    {
            $user = User::where('id',auth()->user()->id)->first();

            $user->fill($request->only([
                'first_name', 
                'last_name', 
                'phone_number', 
            ]));
    
            $user->save();

            if ($request->hasFile('image')) {
                $user->image = $request->file('image');
                $user->save();
            }
        
            return $this->success(new ProfileResource($user) ,__('messages.ProfileController.Profile_Updated_Successfully'));
    }

    public function soft_delete_users_accounts(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            return $this->error(__('messages.ProfileController.User_Not_Found'),404);
        } else {
            $user->delete();
            return $this->success(null ,__('messages.ProfileController.Account_Deleted_Successfully'));
        }
    }


    public function restore_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if (!$user) {
            return $this->error(__('messages.ProfileController.User_Not_Found'),404);
        } else {
            $user->restore();
            return $this->success(null ,__('messages.ProfileController.Account_Restored_Successfully'));
        }
    }


    public function hard_delete_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if (!$user) {
            return $this->error(__('messages.ProfileController.User_Not_Found'),404);
        } else {
            $user->forceDelete();
            return $this->success(null ,__('messages.ProfileController.Account_Deleted_For_Ever'));
        }
    }

    public function soft_delete_my_account()
    {
        $user = User::find(auth()->user()->id);
            $user->delete();
            return $this->success(null ,__('messages.ProfileController.Account_Deleted_Successfully'));
    }


    public function restore_my_account(Request $request)
    {

        $user = User::withTrashed()->where('email', $request->email)->first();

        if (!$user) {
            return $this->error(__('messages.ProfileController.User_Not_Found'),404);
        }else {
            if (!Hash::check($request->password, $user->password)) {
                return $this->error(__('messages.ProfileController.Password_Is_Wrong'),403);
            } else {
                $user->restore();
                return $this->success(null ,__('messages.ProfileController.Account_Restored_Successfully'));
            }
        }
    }

    public function hard_delete_my_account()
    {
        $user = User::withTrashed()->find(auth()->user()->id);
        $user->forceDelete();
        return $this->success(null ,__('messages.ProfileController.Account_Deleted_For_Ever'));
    }


    public function block(Request $request)
    {
        $user = User::find($request->id);
            if (!$user) {
                return $this->error(__('messages.ProfileController.User_Not_Found'),404);
            }
            else {
                $user->blocked = 1;
                $user->save();
                return $this->success(null ,__('messages.ProfileController.Account_Blocked'));
            }
    }


    public function unblock(Request $request)
    {
        $user = User::find($request->id);
            if (!$user) {
                return $this->error(__('messages.ProfileController.User_Not_Found'),404);
            }
            else {
                $user->blocked = 0;
                $user->save();
                return $this->success(null ,__('messages.ProfileController.Account_Active_Now'));
            }
    }

    public function get_all_customers()
    {
        $customers = User::where('role','Customer')->get();
        return $this->success(ProfileResource::collection($customers) ,__('messages.ProfileController.List_All_Customers'));
    }


    public function get_all_merchants()
    {
        $merchants = User::where('role','Merchant')->get();
        return $this->success(ProfileResource::collection($merchants) ,__('messages.ProfileController.List_All_Merchants'));
    }

    public function get_merchant_detail(Request $request)
    {
        $merchant = User::where('role','Merchant')->where('id',$request->id)->get();
        return $this->success(ProfileResource::collection($merchant) ,__('messages.ProfileController.Merchant_Details'));
    }


    public function get_all_employees()
    {
        $employees = User::where('role','Employee')->get();
        return $this->success(ProfileResource::collection($employees) ,__('messages.ProfileController.List_All_Employees'));
    }


    public function get_all_admins()
    {
        $admins = User::where('role','Admin')->get();
        return $this->success(ProfileResource::collection($admins) ,__('messages.ProfileController.List_All_Admins'));
    }
    
}
