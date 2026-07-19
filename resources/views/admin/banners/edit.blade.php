@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Current Image</label><br>
            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" style="width: 200px; margin-bottom: 10px; border-radius: 4px;">
        </div>

        <div class="form-group">
            <label for="image">Replace Image (Leave empty if not changing)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $banner->title) }}">
        </div>

        <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}">
        </div>

        <div class="form-group">
            <label for="button_text">Button Text</label>
            <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}">
        </div>

        <div class="form-group">
            <label for="button_link">Button Link (URL)</label>
            <input type="text" name="button_link" id="button_link" class="form-control" value="{{ old('button_link', $banner->button_link) }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}> Set as Active Banner
            </label>
        </div>

        <button type="submit" class="btn-primary">Update Banner</button>
    </form>
@endsection