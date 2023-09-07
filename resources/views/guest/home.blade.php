@extends('layouts.app')

@section('content')

<div class="jumbotron p-5 mb-4 bg-light rounded-3">
    <div class="container py-5">
        <h1 class="display-5 fw-bold">
            Hello, these are some of my projects
        </h1>
    </div>
</div>

<div class="content">
    @forelse ($projects as $project)
        <div class="card mb-5">
            <img src="{{ $project->screenshot_path }}" class="card-img-top" alt="{{ $project->name }}">
            <div class="card-body">
                <h5 class="card-title">{{ $project->name }}</h5>
                <p class="card-text">{{ $project->description }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Technologies: {{ $project->technologies }}</li>
                
            </ul>
            <div class="card-body">
                <a href="{{ url($project->github_url) }}">
                    <i class="fa-brands fa-github fa-2x"></i>
                </a>
                
            </div>
        </div>
    @empty
        <h1 class="text-danger">Sorry, there are no projects to display.</h1>
    @endforelse
</div>

@endsection
