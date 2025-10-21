<?php

namespace App\Http\Controllers;

use App\Models\LessonNote;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminResourceController extends Controller
{
    public function index()
    {
        $resources = LessonNote::with(['club', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Use facilitator layout if accessed via facilitator route
        $layout = request()->routeIs('facilitator.*') ? 'layouts.facilitator' : 'layouts.admin';
        
        return view('admin.resources.index', compact('resources'))->layout($layout);
    }

    public function create()
    {
        $clubs = Club::orderBy('club_name')->get();
        
        // Use facilitator layout if accessed via facilitator route
        $layout = request()->routeIs('facilitator.*') ? 'layouts.facilitator' : 'layouts.admin';
        
        return view('admin.resources.create', compact('clubs'))->layout($layout);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'club_id' => 'required|exists:clubs,id',
            'attachment_type' => 'required|in:video,document,image,link,audio,code,quiz,assignment,other',
            'visibility' => 'required|in:all,teachers_only,students_only,private',
            'tags' => 'nullable|string',
            // Dynamic validation based on resource type
            'video_url' => 'nullable|url|required_if:attachment_type,video',
            'external_url' => 'nullable|url|required_if:attachment_type,link',
            'link_title' => 'nullable|string|max:255',
            'code_url' => 'nullable|url|required_if:attachment_type,code',
            'code_branch' => 'nullable|string|max:100',
            'audio_url' => 'nullable|url|required_if:attachment_type,audio',
            'attachment' => 'nullable|file|max:51200', // 50MB max
        ]);

        // Validate file upload for file-based resource types
        $fileBasedTypes = ['document', 'image', 'quiz', 'assignment', 'other'];
        if (in_array($validated['attachment_type'], $fileBasedTypes)) {
            $request->validate([
                'attachment' => 'required|file|max:51200',
            ]);
        }

        $attachmentUrl = null;
        $mimeType = null;

        try {
            // Handle different resource types
            switch ($validated['attachment_type']) {
                case 'video':
                    $attachmentUrl = $validated['video_url'];
                    $mimeType = 'video/external';
                    break;
                case 'link':
                    $attachmentUrl = $validated['external_url'];
                    $mimeType = 'link/external';
                    break;
                case 'code':
                    $attachmentUrl = $validated['code_url'] . ($validated['code_branch'] ? '#' . $validated['code_branch'] : '');
                    $mimeType = 'code/repository';
                    break;
                case 'audio':
                    $attachmentUrl = $validated['audio_url'];
                    $mimeType = 'audio/external';
                    break;
                default:
                    // Handle file upload
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('resources', $filename, 'public');
                        $attachmentUrl = 'resources/' . $filename;
                        $mimeType = $file->getMimeType();
                    }
                    break;
            }

            if (!$attachmentUrl) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid resource type or missing content.');
            }

            LessonNote::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'club_id' => $validated['club_id'],
                'attachment_url' => $attachmentUrl,
                'mime_type' => $mimeType,
                'attachment_type' => $validated['attachment_type'],
                'visibility' => $validated['visibility'],
                'tags' => $validated['tags'] ?? null,
                'link_title' => $validated['link_title'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'external_url' => $validated['external_url'] ?? null,
                'code_url' => $validated['code_url'] ?? null,
                'code_branch' => $validated['code_branch'] ?? null,
                'audio_url' => $validated['audio_url'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $redirectRoute = request()->routeIs('facilitator.*') ? 'facilitator.resources.index' : 'admin.resources.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Resource created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create resource. Please try again.');
        }
    }

    public function edit(LessonNote $resource)
    {
        $clubs = Club::orderBy('club_name')->get();
        
        // Use facilitator layout if accessed via facilitator route
        $layout = request()->routeIs('facilitator.*') ? 'layouts.facilitator' : 'layouts.admin';
        
        return view('admin.resources.edit', compact('resource', 'clubs'))->layout($layout);
    }

    public function update(Request $request, LessonNote $resource)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'club_id' => 'required|exists:clubs,id',
            'attachment_type' => 'required|in:video,document,image,link,audio,code,quiz,assignment,other',
            'visibility' => 'required|in:all,teachers_only,students_only,private',
            'tags' => 'nullable|string',
            // Dynamic validation based on resource type
            'video_url' => 'nullable|url|required_if:attachment_type,video',
            'external_url' => 'nullable|url|required_if:attachment_type,link',
            'link_title' => 'nullable|string|max:255',
            'code_url' => 'nullable|url|required_if:attachment_type,code',
            'code_branch' => 'nullable|string|max:100',
            'audio_url' => 'nullable|url|required_if:attachment_type,audio',
            'attachment' => 'nullable|file|max:51200', // 50MB max
        ]);

        // Validate file upload for file-based resource types
        $fileBasedTypes = ['document', 'image', 'quiz', 'assignment', 'other'];
        if (in_array($validated['attachment_type'], $fileBasedTypes)) {
            if (!$request->hasFile('attachment') && !$resource->attachment_url) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'File upload is required for ' . $validated['attachment_type'] . ' type.');
            }
        }

        $attachmentUrl = $resource->attachment_url;
        $mimeType = $resource->mime_type;

        try {
            // Handle different resource types
            switch ($validated['attachment_type']) {
                case 'video':
                    $attachmentUrl = $validated['video_url'];
                    $mimeType = 'video/external';
                    break;
                case 'link':
                    $attachmentUrl = $validated['external_url'];
                    $mimeType = 'link/external';
                    break;
                case 'code':
                    $attachmentUrl = $validated['code_url'] . ($validated['code_branch'] ? '#' . $validated['code_branch'] : '');
                    $mimeType = 'code/repository';
                    break;
                case 'audio':
                    $attachmentUrl = $validated['audio_url'];
                    $mimeType = 'audio/external';
                    break;
                default:
                    // Handle file upload
                    if ($request->hasFile('attachment')) {
                        // Delete old attachment if exists
                        if ($resource->attachment_url && !str_starts_with($resource->attachment_url, 'http')) {
                            Storage::disk('public')->delete($resource->attachment_url);
                        }
                        
                        $file = $request->file('attachment');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('resources', $filename, 'public');
                        $attachmentUrl = 'resources/' . $filename;
                        $mimeType = $file->getMimeType();
                    }
                    break;
            }

            if (!$attachmentUrl) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid resource type or missing content.');
            }

            $resource->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'club_id' => $validated['club_id'],
                'attachment_url' => $attachmentUrl,
                'mime_type' => $mimeType,
                'attachment_type' => $validated['attachment_type'],
                'visibility' => $validated['visibility'],
                'tags' => $validated['tags'] ?? null,
                'link_title' => $validated['link_title'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'external_url' => $validated['external_url'] ?? null,
                'code_url' => $validated['code_url'] ?? null,
                'code_branch' => $validated['code_branch'] ?? null,
                'audio_url' => $validated['audio_url'] ?? null,
            ]);

            $redirectRoute = request()->routeIs('facilitator.*') ? 'facilitator.resources.index' : 'admin.resources.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Resource updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update resource. Please try again.');
        }
    }

    public function destroy(LessonNote $resource)
    {
        if ($resource->attachment_url) {
            Storage::disk('public')->delete($resource->attachment_url);
        }
        $resource->delete();

        $redirectRoute = request()->routeIs('facilitator.*') ? 'facilitator.resources.index' : 'admin.resources.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Resource deleted successfully!');
    }
}
