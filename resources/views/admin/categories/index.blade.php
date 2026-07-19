@extends('admin.layouts.app')

@section('title', 'Manage Categories')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">+ Add New Category</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Category Name</th>
                <th>Slug</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection