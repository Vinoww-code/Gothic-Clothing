@extends('admin.layouts.app')

@section('title', 'Manage Best Sellers')

@section('content')
    <p>Klik tombol di bawah ini untuk menambahkan atau menghapus produk dari daftar Best Seller di halaman utama (Maksimal sarankan 4 produk sesuai desain).</p>

    <table class="table">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Current Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr style="{{ $product->is_best_seller ? 'background-color: #f0fdf4;' : '' }}">
                    <td>
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="Img" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; font-size:10px; display:flex; align-items:center; justify-content:center;">No Img</div>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        @if($product->is_best_seller)
                            <span class="badge-primary">★ Best Seller</span>
                        @else
                            <span class="badge-secondary">Regular</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.bestsellers.toggle', $product->id) }}" method="POST">
                            @csrf
                            @if($product->is_best_seller)
                                <button type="submit" class="btn-danger">Remove from Best Seller</button>
                            @else
                                <button type="submit" class="btn-success">Set as Best Seller</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No products available. Please add products first.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection