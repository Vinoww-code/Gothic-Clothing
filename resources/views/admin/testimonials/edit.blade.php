@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $testimonial->name) }}" required>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $testimonial->location) }}">
        </div>

        <div class="form-group">
            <label for="rating">Rating (1 to 5)</label>
            <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}" required>
        </div>

        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required>{{ old('comment', $testimonial->comment) }}</textarea>
        </div>

        <div class="form-group">
            @if($testimonial->avatar_path)
                <img src="{{ asset('storage/' . $testimonial->avatar_path) }}" alt="Avatar" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-bottom: 10px; display: block;">
            @endif
            <label for="avatar">Replace Avatar (Optional)</label>
            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn-primary">Update Testimonial</button>
    </form>
@endsection