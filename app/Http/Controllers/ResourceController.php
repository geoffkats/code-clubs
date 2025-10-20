<?php

namespace App\Http\Controllers;

use App\Models\LessonNote;
use App\Models\Club;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display resources for a specific club
     */
    public function index($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->authorize('viewClub', $club);
        
        $resources = LessonNote::with('creator')
            ->where('club_id', $clubId)
            ->visibleTo(auth()->user())
            ->latest()
            ->paginate(20);
        
        return view('resources.index', compact('resources', 'clubId', 'club'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->authorize('create', [LessonNote::class, $club]);
        
        return view('resources.create', compact('club'));
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,mp4,jpg,png,jpeg|max:102400', // 100MB
            'visibility' => 'required|in:all,teachers_only',
        ]);

        $validator->after(function ($validator) use ($request) {
            $club = Club::find($request->club_id);
            if ($club && !$this->canCreateResource($club)) {
                $validator->errors()->add('club_id', 'You do not have permission to create resources for this club.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $service = app(FileUploadService::class);
        $result = $service->uploadResource($request->file('file'));
        
        LessonNote::create([
            'club_id' => $request->club_id,
            'title' => $request->title,
            'description' => $request->description,
            'attachment_url' => $result['path'],
            'mime_type' => $result['mime_type'],
            'attachment_type' => $result['type'],
            'visibility' => $request->visibility,
            'created_by' => auth()->id(),
        ]);
        
        return back()->with('success', 'Resource uploaded successfully');
    }

    /**
     * Display a specific resource
     */
    public function show(LessonNote $resource)
    {
        $this->authorize('view', $resource);
        
        return view('resources.show', compact('resource'));
    }

    /**
     * Download a resource file
     */
    public function download(LessonNote $resource)
    {
        $this->authorize('view', $resource);
        
        // Generate signed URL for private resources
        $url = $resource->visibility === 'teachers_only'
            ? Storage::disk('resources')->temporaryUrl($resource->attachment_url, now()->addHour())
            : Storage::disk('resources')->url($resource->attachment_url);
        
        return response()->json(['url' => $url]);
    }

    /**
     * Show the form for editing a resource
     */
    public function edit(LessonNote $resource)
    {
        $this->authorize('update', $resource);
        
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update a resource
     */
    public function update(Request $request, LessonNote $resource)
    {
        $this->authorize('update', $resource);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:all,teachers_only',
        ]);
        
        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
        ]);
        
        return back()->with('success', 'Resource updated successfully');
    }

    /**
     * Delete a resource
     */
    public function destroy(LessonNote $resource)
    {
        $this->authorize('delete', $resource);
        
        // Delete the file from storage
        Storage::disk('resources')->delete($resource->attachment_url);
        
        $resource->delete();
        
        return back()->with('success', 'Resource deleted successfully');
    }

    /**
     * Check if user can create resources for a club
     */
    private function canCreateResource(Club $club): bool
    {
        $user = auth()->user();
        
        // Admin can create resources for any club
        if ($user->user_role === 'admin') {
            return true;
        }
        
        // Facilitator can create resources for clubs they manage
        if ($user->user_role === 'facilitator' && $club->facilitator_id === $user->id) {
            return true;
        }
        
        // Teacher can create resources for clubs they're assigned to
        if ($user->user_role === 'teacher' && $user->clubsAsTeacher->contains($club->id)) {
            return true;
        }
        
        return false;
    }
}
