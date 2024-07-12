<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\profileRequest;
use App\Http\Resources\profileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function get_my_profile()
    {
        $user = auth()->user();
        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'my profile',
                'data' => new ProfileResource($user),
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function update_my_profile(ProfileRequest $request)
    {
        if (auth()->user()) {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = rand(111111,999999) . '.' . $image->getClientOriginalExtension();
                $path = public_path('/Image');
                $image->move($path,$image_name);
            }

            $user = User::where('id',auth()->user()->id)->first();
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->image =  $image_name;
            $user->save();

            if ($user->role == 'Employee') {
                $employee = Employee::where('user_id',$user->id)->first();
                $employee->branch_id = $request->branch_id;
                $employee->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'profile updated',
                'data' => new ProfileResource($user),
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    public function soft_delete_users_accounts(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'account deleted successfully',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    public function restore_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if ($user) {
            $user->restore();

            return response()->json([
                'status' => 200,
                'message' => 'account restored successfully',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    public function hard_delete_users_accounts(Request $request)
    {
        $user = User::withTrashed()->find($request->id);
        if ($user) {
            $user->forceDelete();

            return response()->json([
                'status' => 200,
                'message' => 'account deleted for ever',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }

    public function soft_delete_my_account()
    {
        $user = User::find(auth()->user()->id);
        if ($user) {
            $user->delete();

            return response()->json([
                'status' => 200,
                'message' => 'account deleted successfully',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    public function restore_my_account(Request $request)
    {

        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user) {
        if (Hash::check($request->password, $user->password)) {

            $user->restore();

            return response()->json([
                'status' => 200,
                'message' => 'account restored successfully',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
}
    public function hard_delete_my_account()
    {
        $user = User::withTrashed()->find(auth()->user()->id);
        if ($user) {
            $user->forceDelete();

            return response()->json([
                'status' => 200,
                'message' => 'account deleted for ever',
                'data' => null,
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'user not found',
                'data' => null,
            ]);
        }
    }
    public function block(Request $request)
    {
        $user = User::find($request->id);
            if ($user) {
                $user->blocked = 1;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'account blocked',
                    'data' => null,
                ]);
            }
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'user not found',
                    'data' => null,
                ]);
            }
    }
    public function unblock(Request $request)
    {
        $user = User::find($request->id);
            if ($user) {
                $user->blocked = 0;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'account unblocked',
                    'data' => null,
                ]);
            }
            else {
                return response()->json([
                    'status' => 404,
                    'message' => 'user not found',
                    'data' => null,
                ]);
            }
    }

    public function get_all_customers()
    {
        $customers = User::where('role','Customer')->get();
        return response()->json([
            'status' => 200,
            'message' => 'all customers',
            'data' => ProfileResource::collection($customers),
        ]);
    }
    public function get_all_merchants()
    {
        $merchants = User::where('role','Merchant')->get();
        return response()->json([
            'status' => 200,
            'message' => 'all merchants',
            'data' => ProfileResource::collection($merchants),
        ]);
    }

    public function get_merchant_detail(Request $request)
    {
        $merchant = User::where('role','Merchant')->where('id',$request->id)->get();
        return response()->json([
            'status' => 200,
            'message' => 'merchant detail',
            'data' => ProfileResource::collection($merchant),
        ]);
    }



    public function get_all_employees()
    {
        $employees = User::where('role','Employee')->get();
        return response()->json([
            'status' => 200,
            'message' => 'all employees',
            'data' => ProfileResource::collection($employees),
        ]);
    }
    public function get_all_admins()
    {
        $admins = User::where('role','Admin')->get();
        return response()->json([
            'status' => 200,
            'message' => 'all admins',
            'data' => ProfileResource::collection($admins),
        ]);
    }
}
