<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve projects and paginate the results
        $projects = Project::orderBy('updated_at', 'DESC')->paginate(10);
        // Return the view 'admin.projects.index' with the 'projects' variable
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Create a new Project instance
        $project = new Project();
        // Retrieve all types
        $types = Type::all();
        // Retrieve all technologies
        $technologies = Technology::all();
        // Return the 'admin.projects.create' view with variables
        return view('admin.projects.create', compact('project', 'types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules for project creation
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'nullable|exists:technologies,id',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp',
            'is_featured' => 'nullable|boolean',
            'github_url' => 'required|url',
            'type_id' => 'nullable|exists:types,id',
        ];

        // Custom validation error messages
        $customMessages = [
            'name.required' => 'Name is mandatory',
            'description.required' => 'Description is mandatory',
            'technologies.required' => 'Technologies are mandatory',
            'screenshot.image' => 'Screenshot must be an image',
            'github_url.required' => 'GitHub URL is mandatory',
            'github_url.url' => 'GitHub URL must be a valid URL',
            'type_id.exists' => 'Select a valid category',
        ];

        // Validate the incoming request data
        $validated = $request->validate($rules, $customMessages);

        // Handle project screenshot file upload
        if ($request->hasFile('screenshot')) {
            // Store the uploaded screenshot and update the path
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_path'] = $path;
        }

        // Create a new Project instance with the validated data
        $project = Project::create($validated);

        // Attach selected technologies to the project
        if (array_key_exists('technologies', $validated)) {
            $project->tags()->attach($validated['technologies']);
        }

        // Redirect to the project index page with a success message
        return redirect()->route('admin.projects.index')
            ->with('message', 'Project created successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Return the 'admin.projects.show' view with the 'project' variable
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Retrieve all types
        $types = Type::all();
        // Retrieve all technologies
        $technologies = Technology::all();
        // Get IDs of project technologies
        $project_technologies_ids = $project->technologies->pluck('id')->toArray();
        // Return the 'admin.projects.edit' view with variables
        return view('admin.projects.edit', compact('project', 'types', 'technologies', 'project_technologies_ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Validation rules for updating a project
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'nullable|exists:technologies,id',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp',
            'is_featured' => 'nullable|boolean',
            'github_url' => 'required|url',
            'type_id' => 'nullable|exists:types,id',
        ];

        // Custom validation error messages
        $customMessages = [
            'name.required' => 'Name is mandatory',
            'description.required' => 'Description is mandatory',
            'technologies.required' => 'Technologies are mandatory',
            'screenshot.image' => 'Screenshot must be an image',
            'github_url.required' => 'GitHub URL is mandatory',
            'github_url.url' => 'GitHub URL must be a valid URL',
            'type_id.exists' => 'Select a valid category',
        ];

        // Validate the incoming request data
        $validated = $request->validate($rules, $customMessages);

        // Handle project screenshot file upload
        if ($request->hasFile('screenshot')) {
            // Delete the existing screenshot, if any
            if ($project->screenshot) {
                Storage::disk('public')->delete($project->screenshot);
            }

            // Store the uploaded screenshot and update the path
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_path'] = $path;
        }

        // Update the project with the validated data
        $project->update($validated);

        // Sync or detach project technologies
        if (!Arr::exists($validated, 'technologies') && count($project->technologies)) {
            $project->technologies()->detach();
        } elseif (Arr::exists($validated, 'technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        // Redirect to the project index page with a success message
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

        if (count($project->technologies)) {
            $project->technologies()->detach();
        }

        // Delete the screenshot if it exists
        if ($project->screenshot) {
            Storage::disk('public')->delete($project->screenshot);
        }

        // Delete the project
        $project->delete();

        // Redirect to the project index page with a success message
        return redirect()->route('admin.projects.index')
            ->with('toast-message', 'Project deleted successfully')
            ->with('type', 'success')
            ->with('show_toast', true);
    }

    /**
     * Restore a deleted project.
     */
    public function restore(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();

        // Redirect to the project index page with a success message
        return redirect()->route('admin.projects.index')
            ->with('toast-message', 'Project restored successfully')
            ->with('toast-project-id', $id)
            ->with('type', 'success');
    }
}
