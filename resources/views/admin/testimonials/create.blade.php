@extends('admin.layouts.app')

@section('title', 'Add Testimonial')

@section('content')
    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="location">Location (e.g., Jakarta)</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
        </div>

        <div class="form-group">
            <label for="rating">Rating (1 to 5)</label>
            <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" value="{{ old('rating', 5) }}" required>
        </div>

        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required>{{ old('comment') }}</textarea>
        </div>

        <div class="form-group">
            <label for="avatar">Avatar Image (Optional)</label>
            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn-primary">Save Testimonial</button>
    </form>
@endsection