@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('content')
    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="question">Question</label>
            <input type="text" name="question" id="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
        </div>

        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea name="answer" id="answer" class="form-control" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <button type="submit" class="btn-primary">Update FAQ</button>
    </form>
@endsection