<?php

namespace App\Http\Controllers;

use App\HttpResponse\HttpResponse;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationController extends Controller
{
    
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
        ->withServiceAccount(config_path('firebase_credentials.json'))
        ->createMessaging();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = User::first();
        $user->fcm_token = $request->fcm_token;
        $user->save();
        
        $user->notify(new FirebaseNotification('title','body'));


        return response()->json(['message' => 'FCM token updated successfully.']);
    }

   
}
