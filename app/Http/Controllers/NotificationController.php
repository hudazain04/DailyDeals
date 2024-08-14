<?php

namespace App\Http\Controllers;

use App\HttpResponse\HttpResponse;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotificationController extends Controller
{
    use HttpResponse;
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $notificationId = $request->input('notification_id');
        $notification = Notification::findOrFail($notificationId);

        $users = User::whereIn('id', function ($query) use ($notificationId) {
            $query->select('user_id')
                  ->from('notification_users')
                  ->where('notification_id', $notificationId);
        })->get();

        foreach ($users as $user) {
            if ($user->fcm_token) {
                NotificationFacade::route('fcm', $user->fcm_token)
                    ->notify(new FirebaseNotification($notification->title, $notification->body));
            }
        }
        return $this->success(null ,'Notifications sent successfully');

    }


    public function storeFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user();
        $user->fcm_token = $request->input('fcm_token');
        $user->save();

        return $this->success(null ,'FCM token saved successfully');

    }
}
