<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('updated_at', 'DESC')->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $types = Type::all();
        return view('admin.projects.create', compact('project', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp',
            'is_featured' => 'nullable|boolean',
            'github_url' => 'required|url',
            'type_id' => 'nullable|exists:types,id',
        ];

        $customMessages = [
            'name.required' => 'Name is mandatory',
            'description.required' => 'Description is mandatory',
            'technologies.required' => 'Technologies are mandatory',
            'screenshot.image' => 'Screenshot must be an image',
            'github_url.required' => 'GitHub URL is mandatory',
            'github_url.url' => 'GitHub URL must be a valid URL',
            'type_id.exists' => 'Select a valid category',
        ];

        $validated = $request->validate($rules, $customMessages);

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_path'] = $path;
        }

        $project = Project::create($validated);
        return redirect()->route('admin.projects.index')
            ->with('message', 'Project created successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp',
            'is_featured' => 'nullable|boolean',
            'github_url' => 'required|url',
            'type_id' => 'nullable|exists:types,id',
        ];

        $customMessages = [
            'name.required' => 'Name is mandatory',
            'description.required' => 'Description is mandatory',
            'technologies.required' => 'Technologies are mandatory',
            'screenshot.image' => 'Screenshot must be an image',
            'github_url.required' => 'GitHub URL is mandatory',
            'github_url.url' => 'GitHub URL must be a valid URL',
            'type_id.exists' => 'Select a valid category',
        ];

        $validated = $request->validate($rules, $customMessages);

        if ($request->hasFile('screenshot')) {
            if ($project->screenshot) {
                Storage::disk('public')->delete($project->screenshot);
            }

            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_path'] = $path;
        }

        $project->update($validated);
        return redirect()->route('admin.projects.index')
            ->with('message', 'Project updated successfully')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Save the project to the session before deleting it
        session()->put('deleted_project', $project);

        // Delete the screenshot if it exists
        if ($project->screenshot) {
            Storage::disk('public')->delete($project->screenshot);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('toast-message', 'Project deleted successfully')
            ->with('type', 'success')
            ->with('show_toast', true);
    }

    public function restore(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();
        return redirect()->route('admin.projects.index')
            ->with('toast-message', 'Project restored successfully')
            ->with('toast-project-id', $id)
            ->with('type', 'success');
    }
}
