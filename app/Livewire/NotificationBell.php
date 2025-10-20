<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationBell extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = [
        'refreshNotifications' => '$refresh'
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = auth()->user()->notifications()->limit(10)->get();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->delete();
        $this->loadNotifications();
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'report_awaiting_approval' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'report_approved' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'report_revision_requested' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z',
            'report_rejected' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M15 17h5l-5 5v-5zM4 12a8 8 0 018-8V3a1 1 0 011.414-1.414l2 2A1 1 0 0115 5v2a8 8 0 11-8 8h-3z'
        };
    }

    public function getNotificationColor($type)
    {
        return match($type) {
            'report_awaiting_approval' => 'text-yellow-600',
            'report_approved' => 'text-green-600',
            'report_revision_requested' => 'text-orange-600',
            'report_rejected' => 'text-red-600',
            default => 'text-blue-600'
        };
    }

    public function getNotificationUrl($notification)
    {
        $data = $notification->data;
        
        return match($data['type']) {
            'report_awaiting_approval', 'report_approved', 'report_revision_requested', 'report_rejected' => 
                auth()->user()->user_role === 'teacher' ? '/teacher/reports' : '/facilitator/reports',
            default => '#'
        };
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}