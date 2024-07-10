<?php

namespace App\Http\Controllers;
use App\Http\Requests\faqRequest;
use App\Http\Resources\faqResource;
use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;


class FaqController extends Controller
{
    public function get_faq()
    {
        $faq = FAQ::get();

        return response()->json([
            'status' => 200,
            'message' => 'all Faq',
            'data' => FaqResource::collection($faq),
        ]);
    }
    
    public function add_faq(FaqRequest $request)
    {
        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Faq addes successfully',
            'data' => new FaqResource($faq),
        ]);
    }

    public function update_faq(FaqRequest $request)
    {
        $faq = FAQ::where('id', $request->faq_id)->first();

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Faq updated successfully',
            'data' => new FaqResource($faq),
        ]);
    }

    public function delete_faq(Request $request)
    {
        $faq = FAQ::where('id', $request->faq_id)->first();

        $faq->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Faq deleted successfully',
            'data' => null,
        ]);
    }
}
