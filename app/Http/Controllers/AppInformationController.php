<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppInformationRequest;
use App\Http\Requests\TermsAndConditionsRequest;
use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppInformationController extends Controller
{
    public $privacy;
    public $terms;
    public $about;
    use HttpResponse;
    public function __construct()
    {
        $this->privacy = storage_path('app/privacy_policy.json');
        $this->terms = storage_path('app/terms_and_conditions.json');
        $this->about = storage_path('app/about_app.json');


    }
    public function AddORUpdatePrivacyPolicy(AppInformationRequest $request)
    {

        try{
            if (!File::exists($this->privacy)) {
                return $this->error(['Privacy policy not found'], 404);
            }
            $privacyPolicy = [
                'privacy policy' => $request['content'],
            ];

            File::put($this->privacy, json_encode($privacyPolicy));
            return $this->success(['privacy_policy'=>$privacyPolicy],'privacy policy added successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }

    public function ShowPrivacyPolicy(Request $request)
    {

        try{

            if (!File::exists($this->privacy)) {
                return $this->error( 'Privacy policy not found',404);
            }

            $json = File::get($this->privacy);
            $privacyPolicy = json_decode($json, true);

            return $this->success(['privacy_policy'=>$privacyPolicy],'success');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }


    public function DeletePrivacyPolicy(Request $request)
    {
        try{
            if (File::exists($this->privacy)) {
                File::put($this->privacy, json_encode([]));
                return $this->success(null,'privacy policy deleted successfully');
            }

            return $this->error( 'Privacy policy not found',404);
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AddOrUpdateTermsAndConditions(AppInformationRequest $request)
    {

        try{
            if (!File::exists($this->terms)) {
                return $this->error(['terms and conditions not found'], 404);
            }
            $termsAndConditions = [
                'terms and conditions' => $request['content'],
            ];

            File::put($this->terms, json_encode($termsAndConditions));
            return $this->success(['terms_and_conditions'=>$termsAndConditions],'terms and conditions added successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }

    public function ShowTermsAndConditions(Request $request)
    {

        try{

            if (!File::exists($this->terms)) {
                return response()->json(['message' => 'v not found.'], 404);
            }

            $json = File::get($this->terms);
            $termsAndConditions = json_decode($json, true);

            return $this->success(['terms_and_conditions'=>$termsAndConditions],'success');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }


    public function DeleteTermsAndConditions(Request $request)
    {
        try{
            if (File::exists($this->terms)) {
                File::put($this->terms, json_encode([]));
                return $this->success(null,'terms and conditions deleted successfully');
            }

            return $this->error('terms and conditions not found',404);
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AddOrUpdateAboutApp(AppInformationRequest $request)
    {

        try{
            if (!File::exists($this->about)) {
                return $this->error(['About App not found'], 404);
            }
            $aboutApp = [
                'About App' => $request['content'],
            ];

            File::put($this->about, json_encode($aboutApp));
            return $this->success(['about_app'=>$aboutApp],'About App added successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }

    public function ShowAboutApp(Request $request)
    {

        try{

            if (!File::exists($this->about)) {
                return $this->error( 'About App not found',404);
            }

            $json = File::get($this->about);
            $aboutApp = json_decode($json, true);

            return $this->success(['about_app'=>$aboutApp],'success');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }


    public function DeleteAboutApp(Request $request)
    {
        try{
            if (File::exists($this->about)) {
                File::put($this->about, json_encode([]));
                return $this->success(null,'About App deleted successfully');
            }

            return $this->error( 'About App not found',404);
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }

}
