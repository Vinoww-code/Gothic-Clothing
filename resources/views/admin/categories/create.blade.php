@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            <div class="form-group">
                <label for="type">Category Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="costume">Costume</option>
                    <option value="accessory">Accessory</option>
                </select>
            </div>           
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn-primary">Save Category</button>
    </form>
@endsection