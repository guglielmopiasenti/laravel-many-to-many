@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <!-- Title of the Page -->
                        Edit Project
                    </div>
                    <div class="card-body">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    <!-- Loop through and display each validation error -->
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form for Editing Project -->
                        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf <!-- Cross-site request forgery protection -->
                            @method('PUT') <!-- HTTP method override for update -->

                            <div class="mb-3">
                                <label for="name" class="form-label">Project Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $project->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $project->description) }}</textarea>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Types</label>
                                    <select class="form-select"
                                        @error('type_id', $project->type_id) is-invalid @elseif(old('type_id')) is-valid @enderror
                                        id="type" name="type_id">
                                        <option value="">None</option>
                                        <!-- Loop through project types and populate the dropdown -->
                                        @foreach ($types as $type)
                                            <option @if (old('type_id') == $type->id) selected @endif
                                                value="{{ $type->id }}">{{ $type->label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <!-- Checkboxes for Technologies -->
                                @foreach ($technologies as $technology)
                                    <div class="form-check form-check-inline my-3">
                                        <input class="form-check-input" type="checkbox"
                                            @if (in_array($technology->id, old('technologies', $project_technologies_ids ?? []))) checked @endif id="tech-{{ $technology->id }}"
                                            value="{{ $technology->id }}" name="technologies[]">
                                        <label class="form-check-label"
                                            for="tech-{{ $technology->id }}">{{ $technology->label }}</label>
                                    </div>
                                    @error('technologies')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label for="screenshot" class="form-label">Screenshot</label>
                                <input type="file" class="form-control" id="screenshot" name="screenshot">
                            </div>

                            <div class="mb-3 form-check">
                                <!-- Checkbox for Is Featured -->
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                    {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Is Featured?</label>
                            </div>

                            <div class="mb-3">
                                <label for="github_url" class="form-label">GitHub URL</label>
                                <input type="url" class="form-control" id="github_url" name="github_url"
                                    value="{{ old('github_url', $project->github_url) }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Project</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
