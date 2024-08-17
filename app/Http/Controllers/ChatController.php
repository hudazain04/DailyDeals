<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\HttpResponse\HttpResponse;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    use HttpResponse;
    public function SendMessage(SendMessageRequest $request)
    {
        try {

                    $sender_id = $request->user()->id;
                    $receiver_id = $request->receiver_id;

                    $conversation = Conversation::where(function ($query) use ($sender_id, $receiver_id) {
                        $query->where('user1_id', $sender_id)
                            ->where('user2_id', $receiver_id);
                    })->orWhere(function ($query) use ($sender_id, $receiver_id) {
                        $query->where('user1_id', $receiver_id)
                            ->where('user2_id', $sender_id);
                    })->first();

                    if (!$conversation) {
                        $conversation = Conversation::create([
                            'user1_id' => $sender_id,
                            'user2_id' => $receiver_id,
                        ]);
                    }

//           dd(now()->format('H:i:s'));

            $message=Message::create([
                'message'=>$request->message,
                'receiver_id'=>$request->receiver_id,
                'sender_id'=>$request->user()->id,
                'conversation_id'=>$conversation->id,
                'time' =>now(),

            ]);
//            event(new MessageEvent($request->receiver_id,$message));
            broadcast(new MessageEvent($request->receiver_id, $message))->toOthers();

            return $this->success(MessageResource::make($message),__('messages.successful_request'));
        }
       catch (\Throwable $th)
       {
           return $this->error($th->getMessage(),500);
       }
    }

    public function GetMessages($conversation_id){
        try {
            DB::beginTransaction();
            $conversation=Conversation::find($conversation_id);
            $messages = $conversation->messages()->get();
//            $messages = Message::where('conversation_id' , $conversation_id)->get();
//            Message::where('conversation_id', $conversation_id)->update(['read' => true]);
            $conversation->messages()->latest()->update(['read' => true]);
            DB::commit();
            return $this->success(MessageResource::collection($messages),__('messages.successful_request'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->error($th->getMessage() , 500);
        }
    }
    public function Conversations(Request $request)
    {
        try {
            $user=$request->user()->id;
            $conversations=Conversation::where(function ($query) use ($user) {
                $query->where('user1_id', $user);
            })->orWhere(function ($query) use ($user) {
                $query->where('user2_id', $user);
            })->get();
            return $this->success(ConversationResource::collection($conversations),__('messages.successful_request'));
        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
//    public function getMyConversation() {
//        try {
//            $userId = \request()->user()->id;
//
//            $messages = Message::where(function ($query) use ($userId) {
//                $query->where('from', $userId)
//                    ->orWhere('to', $userId);
//            })->get();
//
//            $conversations = $messages->groupBy(function ($message) use ($userId) {
//                return $message->from === $userId ? $message->to : $message->from;
//            });
//
//            $conversations = $conversations->map(function ($messageGroup, $userId) {
//                $user = User::find($userId);
//                $messages = $messageGroup->all();
//                return (object) [
//                    'user' => $user,
//                    'messages' => $messages,
//                ];
//            });
//
//            return $this->success($conversations);
//        }catch (\Throwable $th){
//            return $this->serverError();
//        }
//    }
}
