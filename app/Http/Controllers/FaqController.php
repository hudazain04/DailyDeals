<?php

namespace App\Http\Controllers;
use App\Http\Requests\faqRequest;
use App\Http\Resources\FaqResource;
use App\Http\Controllers\Controller;
use App\HttpResponse\HttpResponse;
use App\Models\FAQ;
use Illuminate\Http\Request;


class FaqController extends Controller
{
    use HttpResponse;

    public function get_faq()
    {
        $faq = FAQ::get();
        return $this->success(FaqResource::collection($faq) ,'all Faq');

    }

    public function add_faq(FaqRequest $request)
    {
        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return $this->success(new FaqResource($faq) ,'Faq addes successfully');

    }

    public function update_faq(FaqRequest $request)
    {
        $faq = FAQ::where('id', $request->faq_id)->first();

        $faq->fill($request->only([
            'question',
            'answer',
        ]));
        $faq->save();

        return $this->success(new FaqResource($faq) ,'Faq updated successfully');

    }

    public function delete_faq(Request $request)
    {
        $faq = FAQ::where('id', $request->faq_id)->first();
        $faq->delete();
        return $this->success(null ,'Faq deleted successfully');

    }

    public function show_faq(Request $request)
    {
        $faq = FAQ::where('id', $request->faq_id)->first();
        return $this->success(new FaqResource($faq) ,'show Faq');
    }
}
