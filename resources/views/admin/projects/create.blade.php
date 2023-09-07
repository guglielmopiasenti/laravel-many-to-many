@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        Create Project
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

                        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Project Name</label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @elseif(old('name')) is-valid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @elseif(old('description')) is-valid @enderror"
                                    id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                <input type="text"
                                    class="form-control @error('technologies') is-invalid @elseif(old('technologies')) is-valid @enderror"
                                    id="technologies" name="technologies" value="{{ old('technologies') }}" required>
                                @error('technologies')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 d-flex gap-2 align-items-end">
                                <div class="col-11">
                                    <label for="screenshot" class="form-label">Screenshot</label>
                                    <input type="file" class="form-control" id="screenshot" name="screenshot">
                                </div>
                                <div class="col-1">
                                    <img src="{{ isset($project) && $project->screenshot_path ? asset('storage/' . $project->screenshot_path) : 'https://marcolanci.it/utils/placeholder.jpg' }}"
                                        alt="preview" class="img-fluid" id="img-preview">
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                    {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Is Featured?</label>
                            </div>
                            <div class="mb-3">
                                <label for="github_url" class="form-label">GitHub URL</label>
                                <input type="url"
                                    class="form-control @error('github_url') is-invalid @elseif(old('github_url')) is-valid @enderror"
                                    id="github_url" name="github_url" value="{{ old('github_url') }}">
                                @error('github_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create Project</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const placeholder = 'https://marcolanci.it/utils/placeholder.jpg';
        const imageField = document.getElementById('screenshot');
        const previewField = document.getElementById('img-preview');

        imageField.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewField.setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                previewField.setAttribute('src', placeholder);
            }
        });
    </script>
@endsection
