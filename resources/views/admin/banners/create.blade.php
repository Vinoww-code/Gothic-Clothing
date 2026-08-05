@extends('admin.layouts.app')

@section('title', 'Add Banner')

@section('content')
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="image">Banner Image (Required)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle') }}">
        </div>

        <div class="form-group">
            <label for="button_text">Button Text</label>
            <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text') }}">
        </div>

        <div class="form-group">
            <label for="button_link">Pelajari Lebih Lanjut</label>
            <input type="text" name="button_link" id="button_link" class="form-control" value="{{ old('button_link') }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" checked> Set as Active Banner
            </label>
        </div>

        <button type="submit" class="btn-primary">Save Banner</button>
    </form>
@endsection