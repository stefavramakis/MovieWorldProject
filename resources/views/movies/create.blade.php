@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center" style="min-height: 80vh;">
        <div class="card p-4 shadow-sm" style="width: 400px;">
            <h2 class="mb-4 text-center">Add New Movie</h2>

            <form method="POST" action="{{ route('movies.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input id="title" type="text"
                           class="form-control @error('title') is-invalid @enderror"
                           name="title" value="{{ old('title') }}" required autofocus>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Add Movie</button>
            </form>
        </div>
    </div>
@endsection
