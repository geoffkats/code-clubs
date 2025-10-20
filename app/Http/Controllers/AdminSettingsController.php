<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'maintenance_mode' => 'boolean',
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just show a success message
        
        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }
}
