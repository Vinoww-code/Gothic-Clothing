@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            <div class="form-group">
                <label for="type">Category Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="costume" {{ (old('type', $category->type) == 'costume') ? 'selected' : '' }}>Costume</option>
                    <option value="accessory" {{ (old('type', $category->type) == 'accessory') ? 'selected' : '' }}>Accessory</option>
                </select>
            </div>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn-primary">Update Category</button>
    </form>
@endsection