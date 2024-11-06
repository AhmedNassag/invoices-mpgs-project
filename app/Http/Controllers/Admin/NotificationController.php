<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\DatabaseNotification;
use Illuminate\Http\RedirectResponse;

class NotificationController extends BackendController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['notificationList'] = DatabaseNotification::query()
            ->where('notifiable_id', auth()->id())
            ->latest()
            ->get();

        return view('backend.notification.index', $this->data);
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function show($id)
    {
        $notification = DatabaseNotification::query()->findOrFail($id);

        $notification->markAsRead();

        return redirect()->to($notification->url);
    }
}