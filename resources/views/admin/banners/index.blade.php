@extends('admin.layouts.app')

@section('title', 'Manage Banners')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.banners.create') }}" class="btn-primary">+ Add New Banner</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" style="width: 150px; height: auto; border-radius: 4px;">
                    </td>
                    <td>
                        <strong>{{ $banner->title ?? 'No Title' }}</strong><br>
                        <small>{{ $banner->subtitle }}</small>
                    </td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge-success">Active</span>
                        @else
                            <span class="badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No banners found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection