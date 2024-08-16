<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordChangeRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginEmployeeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendVerificationCodeRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\UserResource;
use App\HttpResponse\HttpResponse;
use App\Mail\VerificationMail;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Merchant;
use App\Models\User;
use App\Models\Verification_Code;
use App\Types\UserType;
use App\Types\VerificationCodeType;
use Illuminate\Http\Request;
use App\Services\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Sanctum\PersonalAccessToken;
use function Psy\debug;

class AuthController extends Controller
{
    use HttpResponse;

    public function __construct()
    {

    }


    public function Register(RegisterRequest $request)
    {

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request['password']);
//            $data=$request->only(['first_name','last_name','email','password', 'phone_number','role', 'image'=> $this->UploadImage($request)
//            ]);

//            $imageUrl = AuthController::UploadImage($request);
            $data = $request->only(['first_name', 'last_name', 'email', 'password', 'phone_number', 'role']);
//            $data['image'] = $imageUrl;
            $user = User::create($data);
            if ($request->hasFile('image')) {
                $user->image = $request->file('image');
                $user->save();
            }
            $code=$this->SendVerificationCode($user,VerificationCodeType::register_code);
            DB::commit();
            return $this->success(['user' => UserResource::make($user)],__('messages.auth_controller.register'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }

    }


    public function SendVerificationCode($user , $codeType)
    {

        $verificationCode = rand(1000, 9999);
        while (Verification_Code::where(['code'=>$verificationCode])->exists()){
            $verificationCode = rand(1000, 9999);
        }
        Mail::to($user->email)->send(new VerificationMail($verificationCode));


        $code=Verification_Code::create(['code'=>$verificationCode,'type'=>$codeType,'user_id'=>$user->id]);
        return $code;
    }


    public function VerifyEmail(VerifyRequest $request){
        try {
            DB::beginTransaction();
            $user = User::where('email', $request->email)->first();
            if (!$user){
                return $this->error(__('messages.email_not_found'),404);
            }
            $code = Verification_Code::where('user_id', $user->id)->where('used', false)->where('type', VerificationCodeType::register_code)
                ->where('code' , $request->code)->first();
            if (!$code){
                return $this->error(__('messages.auth_controller.code_incorrect'), 400);
            }
            $user->update(['verified'=>true]);
            $code->update(['used'=>true]);
            $token=$user->createToken('auth_token')->plainTextToken;
            if($request->device_id)
            {
                $currentdevice=$user->devices()->where('user_id', $user->id)->where('device_id', $request->device_id)->first();
                if ($currentdevice){
                    $currentdevice->update([
                        "token" => $token,
                        "notification_token"=>$request->notification_token,
                    ]);
                }else{
                    $de=Device::create([
                        'user_id' => $user->id,
                        'device_id' => $request->device_id,
                        "device_name" => $request->device_name,
                        "token" =>  $token,
                        "notification_token"=>$request->notification_token,
                    ]);
                }
            }

            DB::commit();
            return $this->success(['user'=> UserResource::make($user),'access_token' =>$token] ,__('messages.auth_controller.verified_successfully'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }
    public function ResendVerifyEmailCode(ResendVerificationCodeRequest $request){
        try {
            $user = User::where('email' , $request->email)->first();
            if (!$user){
                return $this->error(__('messages.email_not_found'),404);
            }
            $code=$this->sendVerificationCode($user,VerificationCodeType::register_code);
            return $this->success(['user' => UserResource::make($user),'verification_code'=>$code] ,__('messages.auth_controller.resend_code'));
        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }
    }

    public function Login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->error(__('messages.auth_controller.user_not_found'), 404);
            }
            if (!Auth::attempt($request->only(['email','password']))) {
                return $this->error(__('messages.auth_controller.login_credentials_error'), 401);
            }
            if ($user->blocked) {
                return $this->error(__('messages.error.blocked_account'), 403);
            }
            if (($user->role==UserType::Merchant||$user->role==UserType::Customer)&&!$user->verified) {
                $this->sendVerificationCode($user, 'Register');
                return $this->error(__('messages.auth_controller.account_not_verified'), 403);
            }

            $user = Auth::user();
            $token=$user->createToken('auth_token')->plainTextToken;
            if($request->device_id)
            {
                $currentdevice=$user->devices()->where('user_id', $user->id)->where('device_id', $request->device_id)->first();
                if ($currentdevice){
                    $currentdevice->update([
                        "token" => $token,
                        "notification_token"=>$request->notification_token,
                    ]);
                }else{
                    Device::create([
                        'user_id' => $user->id,
                        "device_name" => $request->device_name,
                        "device_id" => $request->device_id,
                        "token" =>  $token,
                        "notification_token"=>$request->notification_token,
                    ]);
                }
            }

            return $this->success(['user' => UserResource::make($user), "access_token" => $token] ,__('messages.auth_controller.login_successfully' , ['user_name' => $user->full_name]));
        }
        catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }

    }
    public function LoginEmployee(LoginEmployeeRequest $request)
    {
        try{

        $employee=Employee::where('code',$request->code)->first();
        if (! $employee)
        {
            return $this->error(__('messages.auth_controller.user_not_found'), 404);
        }
            $user=User::find($employee->user_id);
//        $user=User::where('id',$employee->user_id)->first();
        $token=$user->createToken('auth_token')->plainTextToken;
//        $currentdevice=$user->devices()->where('user_id', $user->id)->where('device_id', $request->device_id)->first();
//        if ($currentdevice){
//            $currentdevice->update([
//                "token" => $token,
//                "notification_token"=>$request->notification_token,
//
//            ]);
//        }else{
//            Device::create([
//                'user_id' => $user->id,
//                "device_name" => $request->device_name,
//                "device_id" => $request->device_id,
//                "token" =>  $token,
//                "notification_token"=>$request->notification_token,
//            ]);
//        }
        return $this->success(['user' => UserResource::make($user), "access_token" => $token] ,__('messages.auth_controller.login_successfully' , ['user_name' => $user->full_name]) );
    }
        catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }



}

    public function Logout(LogoutRequest $request)
    {
        try {
            $user =$request->user();
            if ($request->device_id)
            {
                $device = Device::where('user_id', $user->id)
                    ->where('device_id', $request->device_id)
                    ->first();

                if ($device) {

                    $tokenParts = explode('|', $device->token);
                    if (count($tokenParts) === 2) {
                        $tokenId = $tokenParts[0];
                        PersonalAccessToken::where('id', $tokenId)->delete();
                    }

                    return $this->success(null, __('messages.auth_controller.logout_successfully'));


                }
                else {
                    return $this->error(__('messages.auth_controller.device_not_found'), 404);
                }
            }
            $request->user()->token()->revoke();
            return $this->success(null,  __('messages.auth_controller.logout_successfully'));




        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }


    public function ChangePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->update(
                    [
                        'password' => Hash::make($request->new_password)
                    ]
                );
                if ($request->device_id)
                {
                    $devices = $user->devices->where('device_id' , "!=" , $request->device_id);
                    foreach ($devices as $device) {

                        $tokenParts = explode('|', $device->token);
                        if (count($tokenParts) === 2) {
                            $tokenId = $tokenParts[0];
                            PersonalAccessToken::where('id', $tokenId)->delete();
                        }


//                    $token=$device->token;
//                    PersonalAccessToken::findToken($token)->delete();
                    }
                }

                return $this->success(['user'=>UserResource::make($user)],  __('messages.auth_controller.password_changed'));

            }

        else{
        return $this->error(__('messages.auth_controller.wrong_current_password'), 400);}

        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }

   }

    public function ForgetPassword(ForgetPasswordRequest $request){
        try {
            $user = User::where('email' , $request->email)->first();
            if (!$user){
                return $this->error(__('messages.auth_controller.user_not_found'),404);
            }
            $code = $this->SendVerificationCode($user , VerificationCodeType::password_code);
            return $this->success(['user' => UserResource::make($user)/*,'verification_code'=>$code*/],__('messages.auth_controller.resend_code'));
        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }
    }
    public function ResendForgetPasswordCode(ResendVerificationCodeRequest $request){
        try {
            $user = User::where('email' , $request->email)->first();
            if (!$user){
                return $this->error(__('messages.auth_controller.user_not_found'),404);
            }
           $code= $this->SendVerificationCode($user,VerificationCodeType::password_code);
            return $this->success(['user' => UserResource::make($user)/*,'verification_code'=>$code*/] ,__('messages.auth_controller.resend_code'));
        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }
    }
    public function ForgetPasswordVerify(VerifyRequest $request){
        try {
            DB::beginTransaction();
            $user = User::where('email', $request->email)->first();
            if (!$user){
                return $this->error(__('messages.auth_controller.user_not_found'),404);
            }
            $code = Verification_Code::where('code' , $request->code)->where('user_id' , $user->id)->where('used' , false)
                ->where('type' , VerificationCodeType::password_code)->first();
            if (!$code){
                return $this->error(__('messages.auth_controller.code_incorrect'), 400);
            }
            $code->update(['used'=>true]);
            DB::commit();
            return $this->success(['user' => UserResource::make($user)] , __('messages.auth_controller.verified_successfully'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }
    }

    public function ForgetPasswordChange(ForgetPasswordChangeRequest $request){
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user){
                return $this->error(__('messages.auth_controller.user_not_found'),404);
            }
            $user->update($request->only(['password']));
            return $this->success(['user'=>UserResource::make($user)] ,__('messages.auth_controller.password_changed'));
        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
        }
    }



}
