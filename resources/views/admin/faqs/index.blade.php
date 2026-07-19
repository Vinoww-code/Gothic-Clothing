@extends('admin.layouts.app')

@section('title', 'Manage FAQ')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary">+ Add New FAQ</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Question</th>
                <th>Answer</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($faqs as $faq)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $faq->question }}</strong></td>
                    <td>{{ Str::limit($faq->answer, 60) }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn-warning">Edit</a>
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No FAQs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection