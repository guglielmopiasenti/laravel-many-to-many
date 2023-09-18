@extends('layouts.app')

@section('title', 'Project')

@section('content')
    <!-- Project Header -->
    <header>
        <h1 class="text-center display-1 my-4">{{ $project->name }}</h1>
    </header>
    <div class="d-flex align-items-center justify-content-between gap-3">
        <!-- Project Screenshot -->
        <img src="{{ asset('storage/' . $project->screenshot_path) }}" alt="Project Screenshot">
        <!-- Project Information Card -->
        <div class="card p-3">
            <p>{{ $project->description }}</p>
            <div><strong>Type:</strong> {{ $project->type?->label }}</div>
            {{-- <div><strong>Technologies:</strong> {{ $project->technologies }}</div> --}}
            <!-- Featured and GitHub Links -->
            <div class="d-flex align-items-center justify-content-between my-5">
                <div class="d-flex gap-3">
                    <!-- Display Star Icon for Featured Projects -->
                    <div>
                        @if ($project->is_featured)
                            <i class="fa-solid fa-star fa-2x" style="color: #fbff00;"></i>
                        @else
                            <i class="fa-regular fa-star fa-2x" style="color: #fbff00;"></i>
                        @endif
                    </div>
                    <!-- Link to GitHub -->
                    <a href="{{ url($project->github_url) }}">
                        <i class="fa-brands fa-github fa-2x"></i>
                    </a>
                </div>
                <!-- Edit and Delete Actions -->
                <div class="d-flex align-items-center">
                    <!-- Edit Project Button -->
                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-warning ms-2">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                    <!-- Delete Project Form with CSRF and Method Spoofing -->
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="delete-form">
                        @csrf <!-- Cross-site request forgery protection -->
                        @method('DELETE') <!-- HTTP method override for delete -->
                        <button class="btn btn-sm btn-danger ms-2">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Project Created and Modified Dates -->
            <div class="d-flex gap-4">
                <div>
                    <strong>Created at: </strong> {{ $project->created_at }}
                </div>
                <div>
                    <strong>Modified at: </strong>{{ $project->updated_at }}
                </div>
            </div>
        </div>
    </div>
    <!-- "Go Back" Button -->
    <div class="d-flex justify-content-center my-5">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary text-center">Go Back</a>
    </div>
@endsection

@section('scripts')
    <!-- Include JavaScript for delete confirmation -->
    @vite('resources/js/delete-confirmation.js')
@endsection
