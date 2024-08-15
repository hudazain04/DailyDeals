<?php

namespace App\Http\Controllers;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\NumberResource;
use App\Http\Resources\RecentProductResource;
use App\Models\Branch;
use App\Models\Number;
use App\Models\Employee;
use App\Models\Store;
use App\Http\Controllers\Controller;
use App\HttpResponse\HttpResponse;
use App\Models\QR;
use Illuminate\Http\Request;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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
///////////////////
        for ($rating = 1; $rating <= 5; $rating++) {

            $qrCodeData = json_encode(['branch_id' => $branch->id, 'rate' => $rating]);

            $from = [255, 0, 0];
            $to = [0, 0, 255];
            $qrCode =QrCode::format('svg')
                ->size(200)
                ->style('dot')
                ->eye('circle')
                ->gradient($from[0], $from[1], $from[2], $to[0], $to[1], $to[2], 'diagonal')
                ->margin(1)
                ->generate($qrCodeData);

//            make it unique by uuid or timestamp
            $fileName =  'branch_' . $branch->id . '_rating_' . $rating . '.svg';
            $filePath = public_path('QrImage/' . $fileName);
            file_put_contents($filePath, $qrCode);
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $filePath,
                $fileName,
                'image/png',
                null,
                true
            );

             QR::create([
                 'branch_id' => $branch->id,
                 'rate' => $rating,
                 'image' => '/QrImage/' . $fileName
             ]);
        }








            //////////////
//            for ($i=1; $i <= 5; $i++) {
//                QR::create([
//                    'rate' => $i,
//                    'branch_id' => $branch->id,
//                    'image' =>  $request->file('rate' . $i),
//                ]);
//            }

return $this->success(new BranchResource($branch) ,__('messages.BranchController.Branch_Added_Successfully'));
    }



    public function update_branch(BranchRequest $request)
    {
        if (auth()->user()->role != 'Merchant' && auth()->user()->role != 'Employee') {
            return $this->error(__('messages.BranchController.You_Are_Not_Authorized'),403);

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


                    return $this->success(new BranchResource($branch) ,__('messages.BranchController.Branch_Updated_Successfully'));
            }
        }
    }


    public function delete_branch(Request $request)
    {
            $branch = Branch::where('id',$request->branch_id)->first();
            $store = Store::where('id',$branch->store_id)->first();

            if ($store->merchant_id != auth()->user()->id) {
                return $this->error(__('messages.BranchController.You_Are_Not_Authorized'),403);
            } else {
                $branch->delete();
                return $this->success(null ,__('messages.BranchController.Branch_Deleted_Successfully'));
            }
    }


    public function list_merchant_branches(Request $request)
    {
            $store = Store::where('id',$request->store_id)->first();
            if ($store->merchant_id != auth()->user()->id) {
                return $this->error(__('messages.BranchController.You_Are_Not_Authorized'),403);
            } else {
                $branches = Branch::where('store_id',$request->store_id)->get();
                return $this->success(BranchResource::collection($branches) ,__('messages.BranchController.List_Branches'));
            }
    }


    public function list_employee_branches()
    {
            $employee = Employee::where('user_id',auth()->user()->id)->first();
            $branches = Branch::where('id',$employee->branch_id)->get();
            return $this->success(BranchResource::collection($branches) ,__('messages.BranchController.List_Branches'));
    }


    public function list_customer_branches(Request $request)
    {
            $branches = Branch::where('visible',1)->where('store_id',$request->store_id)->get();
            return $this->success(BranchResource::collection($branches) ,__('messages.BranchController.List_Branches'));
    }

    public function list_admin_branches(Request $request)
    {
            $branches = Branch::where('store_id',$request->store_id)->get();
            return $this->success(BranchResource::collection($branches) ,__('messages.BranchController.List_Branches'));
    }

    public function Branch_byID (Request $request)
    {
        $branches = Branch::where('id',$request->id)->get();
        return $this->success(BranchResource::collection($branches) ,__('messages.BranchController.List_Branch'));
    }

    public function branch_info (Request $request)
    {
        $branch = Branch::where('id',$request->id)->first();
        $rates = $branch->rates()->pluck('rate')->toArray();
        $complaints = $branch->complaints()->count();
        $products = $branch->products()->count();
        $offers = $branch->offers()->count();

         $data = [
            'products' => $products,
            'offers' => $offers,
            'rate' => array_sum($rates) / count($rates),
            'complaints' => $complaints,
        ];

        return $this->success($data ,__('messages.BranchController.Branch_Info'));
    }


    public function recent_products (Request $request)
    {
        $branch = Branch::where('id',$request->id)->first();
        $products = $branch->products()->orderBy('created_at','desc')->take(5)->get();

        return $this->success(RecentProductResource::collection($products) ,__('messages.BranchController.List_Recent_Products'));
    }

    public function yearly_rate(Request $request) {

        $currentYear = Carbon::now()->year;

        $monthlyAverages = DB::table('rates')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('ROUND(AVG(rate)) as average_rate')
            )
            ->where('branch_id', $request->id)
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->pluck('average_rate', 'month')
            ->toArray();
    
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[] = $monthlyAverages[$month] ?? 0;
        }
    
        return $this->success($data ,__('messages.BranchController.List_Yearly_Rates'));

    }

}
