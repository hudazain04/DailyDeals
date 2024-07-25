<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\profileRequest;
use App\Http\Resources\profileResource;
use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use HttpResponse;
    
    public function get_my_profile()
    {
        $user = auth()->user();
        return $this->success(new ProfileResource($user) ,'my profile');
    }


    public function update_my_profile(profileRequest $request)
    {
            $user = User::where('id',auth()->user()->id)->first();
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->image = $request->file('image');
            $user->save();
        
            return $this->success(new ProfileResource($user) ,'profile updated successfully');
    }


    public function soft_delete_users_accounts(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            return $this->error('user not found',404);
        } else {
            $user->delete();
            return $this->success(null ,'account deleted successfully');
        }
    }


    public function restore_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if (!$user) {
            return $this->error('user not found',404);
        } else {
            $user->restore();
            return $this->success(null ,'account restored successfully');
        }
    }


    public function hard_delete_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if (!$user) {
            return $this->error('user not found',404);
        } else {
            $user->forceDelete();
            return $this->success(null ,'account deleted for ever');
        }
    }

    public function soft_delete_my_account()
    {
        $user = User::find(auth()->user()->id);
            $user->delete();
            return $this->success(null ,'account deleted successfully');
    }


    public function restore_my_account(Request $request)
    {

        $user = User::withTrashed()->where('email', $request->email)->first();

        if (!$user) {
            return $this->error('user not found',404);
        }else {
            if (!Hash::check($request->password, $user->password)) {
                return $this->error('password is wrong',403);
            } else {
                $user->restore();
                return $this->success(null ,'account restored successfully');
            }
        }
    }

    public function hard_delete_my_account()
    {
        $user = User::withTrashed()->find(auth()->user()->id);
        $user->forceDelete();
        return $this->success(null ,'account deleted for ever');
    }


    public function block(Request $request)
    {
        $user = User::find($request->id);
            if (!$user) {
                return $this->error('user not found',404);
            }
            else {
                $user->blocked = 1;
                $user->save();
                return $this->success(null ,'account blocked');
            }
    }


    public function unblock(Request $request)
    {
        $user = User::find($request->id);
            if (!$user) {
                return $this->error('user not found',404);
            }
            else {
                $user->blocked = 0;
                $user->save();
                return $this->success(null ,'account unblocked');
            }
    }

    public function get_all_customers()
    {
        $customers = User::where('role','Customer')->get();
        return $this->success(ProfileResource::collection($customers) ,'all customers');
    }


    public function get_all_merchants()
    {
        $merchants = User::where('role','Merchant')->get();
        return $this->success(ProfileResource::collection($merchants) ,'all merchants');
    }

    public function get_merchant_detail(Request $request)
    {
        $merchant = User::where('role','Merchant')->where('id',$request->id)->get();
        return $this->success(ProfileResource::collection($merchant) ,'merchant detail');
    }


    public function get_all_employees()
    {
        $employees = User::where('role','Employee')->get();
        return $this->success(ProfileResource::collection($employees) ,'all employees');
    }


    public function get_all_admins()
    {
        $admins = User::where('role','Admin')->get();
        return $this->success(ProfileResource::collection($admins) ,'all admins');
    }
    
}
