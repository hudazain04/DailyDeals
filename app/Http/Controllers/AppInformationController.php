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
                return $this->error([__('messages.app_info_controller.privacy_policy_not_found')], 404);
            }
            $privacyPolicy = [
                'privacy policy' => $request['content'],
            ];

            File::put($this->privacy, json_encode($privacyPolicy));
            return $this->success(['privacy_policy'=>$privacyPolicy],__('messages.app_info_controller.create_privacy_policy'));
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
                return $this->error( __('messages.app_info_controller.privacy_policy_not_found'),404);
            }

            $json = File::get($this->privacy);
            $privacyPolicy = json_decode($json, true);

            return $this->success(['privacy_policy'=>$privacyPolicy],__('messages.successful_request'));
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
                return $this->success(null,__('messages.app_info_controller.delete_privacy_policy'));
            }

            return $this->error( __('messages.app_info_controller.privacy_policy_not_found'),404);
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
                return $this->error([__('messages.app_info_controller.terms_and_conditions_not_found')], 404);
            }
            $termsAndConditions = [
                'terms and conditions' => $request['content'],
            ];

            File::put($this->terms, json_encode($termsAndConditions));
            return $this->success(['terms_and_conditions'=>$termsAndConditions],__('messages.app_info_controller.create_terms_and_conditions'));
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

                return $this->error(__('messages.app_info_controller.terms_and_conditions_not_found'),404);

            }

            $json = File::get($this->terms);
            $termsAndConditions = json_decode($json, true);

            return $this->success(['terms_and_conditions'=>$termsAndConditions],__('messages.successful_request'));
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
                return $this->success(null,__('messages.app_info_controller.delete_terms_and_conditions'));
            }

            return $this->error(__('messages.app_info_controller.terms_and_conditions_not_found'),404);
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
                return $this->error([__('messages.app_info_controller.about_app_not_found')], 404);
            }
            $aboutApp = [
                'About App' => $request['content'],
            ];

            File::put($this->about, json_encode($aboutApp));
            return $this->success(['about_app'=>$aboutApp],__('messages.app_info_controller.create_about_app'));
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
                return $this->error( __('messages.app_info_controller.about_app_not_found'),404);
            }

            $json = File::get($this->about);
            $aboutApp = json_decode($json, true);

            return $this->success(['about_app'=>$aboutApp],__('messages.successful_request'));
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
                return $this->success(null,__('messages.app_info_controller.delete_about_app'));
            }

            return $this->error( __('messages.app_info_controller.about_app_not_found'),404);
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }

}
