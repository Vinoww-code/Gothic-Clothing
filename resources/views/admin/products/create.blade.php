@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="price_per_day">Price Per Day (Rp)</label>
            <input type="number" name="price_per_day" id="price_per_day" class="form-control" value="{{ old('price_per_day') }}" required>
            @error('price_per_day') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="images">Product Images (Can select multiple)</label>
            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple required>
            <small style="color: #666;">Hold CTRL / CMD to select multiple images at once.</small>
            @error('images.*') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group mb-3">
    <label>Tersedia Ukuran</label><br>
    @php $sizeOptions = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']; @endphp
    @foreach($sizeOptions as $size)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}">
            <label class="form-check-label" for="size_{{ $size }}">{{ $size }}</label>
        </div>
    @endforeach
    </div>

    <div class="form-group mb-3">
        <label>Tersedia Warna</label><br>
        @php $colorOptions = ['Red', 'Maroon', 'Purple', 'White', 'Grey', 'Black']; @endphp
        @foreach($colorOptions as $color)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $color }}" id="color_{{ $color }}">
                <label class="form-check-label" for="color_{{ $color }}">{{ $color }}</label>
            </div>
        @endforeach
    </div>

        <button type="submit" class="btn-primary">Save Product</button>
    </form>
@endsection