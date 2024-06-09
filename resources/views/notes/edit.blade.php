@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Note</h1>
    <form action="{{ route('notes.update', $note->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="form-input mt-1 block w-full" value="{{ $note->title }}" required>
        </div>
        <div class="mb-4">
            <label for="body" class="block text-gray-700">Body</label>
            <textarea name="body" id="body" class="form-textarea mt-1 block w-full" required>{{ $note->body }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>
@endsection
