<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\AddOfferTypeRequestRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OfferTypeResource;
use App\HttpResponse\HttpResponse;
use App\Models\Comment;
use App\Models\Offer;
use App\Models\Type_Of_Offer_Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{

    use HttpResponse;
    public function AddOfferTypeRequest(AddOfferTypeRequestRequest $request)
    {
        try {
            $user=$request->user();
            $offer_type=Type_Of_Offer_Request::create(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                    'user_id'=>$user->id,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),'offer type requested successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function UpdateOfferTypeRequest($request_id,AddOfferTypeRequestRequest $request)
    {
        try {
            $offer_type=Type_Of_Offer_Request::find($request_id);
//            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->update(
                [
                    'type'=>$request->type,
                    'description'=>$request->description,
                ]
            );
            return $this->success(OfferTypeResource::make($offer_type),'offer type request updated successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function DeleteOfferTypeRequest($request_id)
    {
        try {
            $offer_type=Type_Of_Offer_Request::find($request_id);
//            $offer_type=Type_Of_Offer_Request::where('id',$request_id)->first();
            $offer_type->delete();
            return $this->success(null,'offer type request deleted successfully');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function GetAll()
    {
        try {
            $offer_types=Type_Of_Offer_Request::all();
            return $this->success(OfferTypeResource::collection($offer_types),'offer type requests');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllForUser(Request $request)
    {
        try {
            $user=$request->user();
            $offer_types=Type_Of_Offer_Request::where('user_id',$user->id)->get();
            return $this->success(OfferTypeResource::collection($offer_types),'offer type requests');

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function AddComment(AddCommentRequest $request)
    {
        try {
            $comment=Comment::create([
               'comment'=>$request->comment,
               'offer_id'=>$request->offer_id,
               'customer_id'=>Auth::user()->id,
            ]);
            return $this->success(CommentResource::make($comment),'comment added successfully');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }

    }
    public function UpdateComment($comment_id,UpdateCommentRequest $request)
    {
        try {
            $comment=Comment::find($comment_id);
//            $comment=Comment::where('id',$comment_id)->first();
            if ($comment->customer_id != $request->user()->id)
            {
                return $this->error('this is not your comment',400);
            }
            else
            {
                $comment->update([
                    'comment'=>$request->comment,
                ]);
                return $this->success(CommentResource::make($comment),'comment updated successfully');
            }

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }

    public function DeleteComment($comment_id,Request $request)
    {
        try {
            $comment=Comment::find($comment_id);
//            $comment=Comment::where('id',$comment_id)->first();
            if ($comment->customer_id != $request->user()->id)
            {
                return $this->error('this is not your comment',400);
            }
            else
            {
                $comment->delete();
                return $this->success(null,'comment deleted successfully');
            }

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
    public function GetAllCommentsOnOffer($offer_id)
    {
        try {
            $comments=Comment::where('offer_id',$offer_id)->get();
            return $this->success(CommentResource::collection($comments),'all comments');
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }


}
