@extends('layouts.app')

@section('content')
@session('success')
    <div class="p-4 bg-green-100">
        {{ $value }}
    </div>
@endsession
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Your Notes</h1>
    <a href="{{ route('notes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Note</a>
    @foreach($notes as $note)
        <div class="bg-white shadow-md rounded p-4 mt-4">
            <h5 class="text-xl font-semibold">{{ $note->title }}</h5>
            <p class="text-gray-700">{{ $note->body }}</p>
            <p class="text-gray-500 text-sm mt-2">Created at: {{ $note->created_at->format('F j, Y, g:i a') }}</p>
            <p class="text-gray-500 text-sm">Updated at: {{ $note->updated_at->format('F j, Y, g:i a') }}</p>
            <div class="mt-4">
                <a href="{{ route('notes.edit', $note->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
