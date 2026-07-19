@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id') ?? $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="price_per_day">Price Per Day (Rp)</label>
            <input type="number" name="price_per_day" id="price_per_day" class="form-control" value="{{ old('price_per_day', $product->price_per_day) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="available" {{ (old('status') ?? $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                <option value="rented" {{ (old('status') ?? $product->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                <option value="maintenance" {{ (old('status') ?? $product->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        <div class="form-group">
            <label>Current Images</label>
            <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 10px;">
                @foreach($product->images as $image)
                    <div style="position: relative; border: 1px solid #ccc; padding: 5px; border-radius: 4px;">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Img" style="width: 100px; height: 100px; object-fit: cover;">
                        
                        <!-- Form kecil untuk menghapus gambar spesifik -->
                        <button type="submit" form="delete-img-{{ $image->id }}" style="position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px; line-height: 1;">&times;</button>
                    </div>
                @endforeach
            </div>
            @if($product->images->count() == 0)
                <p style="color: #666; font-size: 14px;">No images uploaded.</p>
            @endif
        </div>

        <div class="form-group">
            <label for="images">Add More Images (Leave empty if not adding)</label>
            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
        </div>
        <div class="form-group mb-3">
    <label>Tersedia Ukuran</label><br>
    @php 
        $sizeOptions = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']; 
        $currentSizes = old('sizes', $product->sizes ?? []);
    @endphp
    @foreach($sizeOptions as $size)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}" {{ in_array($size, $currentSizes) ? 'checked' : '' }}>
            <label class="form-check-label" for="size_{{ $size }}">{{ $size }}</label>
        </div>
    @endforeach
    </div>

    <div class="form-group mb-3">
        <label>Tersedia Warna</label><br>
        @php 
            $colorOptions = ['Red', 'Maroon', 'Purple', 'White', 'Grey', 'Black']; 
            $currentColors = old('colors', $product->colors ?? []);
        @endphp
        @foreach($colorOptions as $color)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $color }}" id="color_{{ $color }}" {{ in_array($color, $currentColors) ? 'checked' : '' }}>
                <label class="form-check-label" for="color_{{ $color }}">{{ $color }}</label>
            </div>
        @endforeach
    </div>

        <button type="submit" class="btn-primary">Update Product</button>
    </form>

    <!-- Hidden Forms for Deleting Individual Images -->
    @foreach($product->images as $image)
        <form id="delete-img-{{ $image->id }}" action="{{ route('admin.products.images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Delete this image?');" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection