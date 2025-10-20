<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationSettingsController extends Controller
{
    public function index()
    {
        return view('admin.notifications.settings');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'report_notifications' => 'boolean',
            'assessment_notifications' => 'boolean',
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just show a success message
        
        return redirect()->route('admin.notifications.settings')
            ->with('success', 'Notification settings updated successfully.');
    }
}
