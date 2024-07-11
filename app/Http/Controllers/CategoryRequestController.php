<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptCategoryRequestRequest;
use App\Http\Requests\AddCategoryRequestRequest;
use App\Http\Requests\UpdateCategoryRequestRequest;
use App\Http\Resources\CategoryRequestResource;
use App\Http\Resources\CategoryResource;
use App\HttpResponse\HttpResponse;
use App\Models\Category;
use App\Models\Category_Request;
use App\Types\RequestType;
use App\Types\UserType;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function NunoMaduro\Collision\Exceptions\getMessage;
use function Symfony\Component\Clock\get;

class CategoryRequestController extends Controller
{
    use HttpResponse;
    public function GetCategoryRequest($request_id)
    {
        try
        {
            $category=Category_Request::where('id',$request_id)->first();

                return $this->success(['category_request'=>CategoryRequestResource::make($category)],'success');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function AddCategoryRequest(AddCategoryRequestRequest $request)
    {
        try
        {
            $user=Auth::user();
            $category=Category_Request::create(['category'=>$request->category,'user_id'=>$user->id,'status'=>RequestType::Pending]);
            return $this->success(['category_request'=>CategoryRequestResource::make($category)],
                'category request added successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateCategoryRequest($request_id,UpdateCategoryRequestRequest $request)
    {
        try
        {
            $category=Category_Request::where('id',$request_id)->first();
            if ($category)

                $category->update(['category'=>$request->category]);
                return $this->success(['category_request'=>CategoryRequestResource::make($category)],
                    'category request updated successfully');


        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function DeleteCategoryRequest($request_id)
    {
        try
        {
            $category=Category_Request::where('id',$request_id)->first();

                $category->delete();
                return $this->success(null,'category request deleted successfully');


        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllForUser()
    {
        $user=Auth::user();
        try
        {
            $status=[RequestType::Pending,RequestType::Accepted,RequestType::Rejected];
            $categories=Category_Request::where('user_id',$user->id)
                ->orderByRaw('FIELD(status,?,?,?)',$status)
                ->get();


            return $this->success(['category_requests'=>CategoryRequestResource::collection($categories)],'success');


        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAll()
    {
        try {
            $status=[RequestType::Pending,RequestType::Accepted,RequestType::Rejected];
            $categories=Category_Request::orderByRaw('FIELD(status,?,?,?)',$status)->get();

                return $this->success(['category_requests'=>CategoryRequestResource::collection($categories)],'success');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function AcceptCategoryRequest($request_id,AcceptCategoryRequestRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $category_request=Category_Request::where('id',$request_id)->first();
            if ($request['admin_name'])
            {
                $category_request->update(['admin_name'=>$request->admin_name,'status'=>RequestType::Accepted]);
                if ($request['parent_category'])
                {
                    $parent=Category::where('category',$request->parent_category)->first();
                    $category=Category::create(['category'=>$request->admin_name,
                        'parent_category'=>$parent->id,'priority'=>$request->priority]);
                    DB::commit();
                    return $this->success(['category'=>CategoryResource::make($category)],'category request accepted');
                }
                else
                {
                    $category=Category::create(['category'=>$request->admin_name,'priority'=>$request->priority]);
                    DB::commit();
                    return $this->success(['category'=>CategoryResource::make($category)],'category request accepted');
                }


            }
            else
            {
                $category_request->update(['status'=>RequestType::Accepted]);
                if ($request['parent_category'])
                {
                    $parent=Category::where('category',$request->parent_category)->first();
                    $category=Category::create(['category'=>$request->category,
                        'parent_category'=>$parent->id,'priority'=>$request->priority]);
                    DB::commit();
                    return $this->success(['category'=>CategoryResource::make($category)],'category request accepted');
                }
                else
                {
                    $category=Category::create(['category'=>$request->category,'priority'=>$request->priority]);
                    DB::commit();
                    return $this->success(['category'=>CategoryResource::make($category)],'category request accepted');
                }

            }

        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->error($th->getMessage(),500);
        }

    }
    public function RejectCategoryRequest($request_id)
    {
        try {
            $category_request=Category_Request::where('id',$request_id)->first();
            $category_request->update(['status'=>RequestType::Rejected]);
            return $this->success(null,'category request rejected');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }




}
