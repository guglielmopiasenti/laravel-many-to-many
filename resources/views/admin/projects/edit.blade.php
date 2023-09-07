@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        Edit Project
                    </div>
                    <div class="card-body">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                                        @foreach ($types as $type)
                                            <option @if(old('type_id') == $type->id) selected @endif value="{{ $type->id }}">{{ $type->label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="technologies" class="form-label">Technologies</label>
                                <input type="text" class="form-control" id="technologies" name="technologies"
                                    value="{{ old('technologies', $project->technologies) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="screenshot" class="form-label">Screenshot</label>
                                <input type="file" class="form-control" id="screenshot" name="screenshot">
                            </div>
                            <div class="mb-3 form-check">
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
