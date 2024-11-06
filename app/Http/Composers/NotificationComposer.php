<?php

namespace App\Http\Composers;

use App\Models\DatabaseNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class NotificationComposer
{

    public function compose(View $view): void
    {
        $view->with('notifications', $this->notifications());
    }

    private function notifications(): Collection|array
    {
        return DatabaseNotification::query()
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();
    }
}