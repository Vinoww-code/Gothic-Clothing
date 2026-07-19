@extends('admin.layouts.app')

@section('title', 'Add FAQ')

@section('content')
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="question">Question</label>
            <input type="text" name="question" id="question" class="form-control" value="{{ old('question') }}" required>
        </div>

        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea name="answer" id="answer" class="form-control" rows="5" required>{{ old('answer') }}</textarea>
        </div>

        <button type="submit" class="btn-primary">Save FAQ</button>
    </form>
@endsection