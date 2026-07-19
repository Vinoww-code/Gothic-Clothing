@extends('admin.layouts.app')

@section('title', 'Manage Testimonials')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">+ Add New Testimonial</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Name / Location</th>
                <th>Rating</th>
                <th>Comment</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testimonials as $testimonial)
                <tr>
                    <td>
                        @if($testimonial->avatar_path)
                            <img src="{{ asset('storage/' . $testimonial->avatar_path) }}" alt="Avatar" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                        @else
                            <div style="width: 50px; height: 50px; background: #eee; border-radius: 50%; display:flex; align-items:center; justify-content:center; font-size:10px;">No Img</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $testimonial->name }}</strong><br>
                        <small>{{ $testimonial->location ?? '-' }}</small>
                    </td>
                    <td>{{ $testimonial->rating }} / 5</td>
                    <td>{{ Str::limit($testimonial->comment, 50) }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('Delete this testimonial?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No testimonials found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection