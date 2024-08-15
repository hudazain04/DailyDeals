<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\HttpResponse\HttpResponse;
use App\Models\Complaint;
use Illuminate\Http\Request;


class ComplaintController extends Controller
{
    use HttpResponse;
    
    public function list_all_complaints()
    {
        $complaints = Complaint::get();
        return $this->success(ComplaintResource::collection($complaints) ,__('messages.ComplaintController.List_All_Complaints'));
    }

    public function complaint_details(Request $request)
    {
        $complaint = Complaint::findOrFail($request->complaint_id);
        return $this->success(new ComplaintResource($complaint) ,__('messages.ComplaintController.Show_Complaint_Details'));
    }
    
    public function add_complaint(ComplaintRequest $request)
    {
        $user = auth()->user();
        
        $complaint = Complaint::create([
            'customer_id' => $user->id,
            'branch_id' => $request->branch_id,
            'complaint' => $request->complaint,
        ]);

        return $this->success(new ComplaintResource($complaint) ,__('messages.ComplaintController.Complaint_Added_Successfully'));
        }

    }

