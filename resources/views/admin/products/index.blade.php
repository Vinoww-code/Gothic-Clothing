@extends('admin.layouts.app')

@section('title', 'Manage Products')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Add New Product</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price / Day</th>
                <th>Status</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="Img" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #eee; border-radius: 4px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#888;">No Img</div>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</td>
                    <td>
                        @if($product->status == 'available')
                            <span class="badge-success">Available</span>
                        @elseif($product->status == 'rented')
                            <span class="badge-warning">Rented</span>
                        @else
                            <span class="badge-danger">Maintenance</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection