@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8 mt-8 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold mb-6 mt-2 text-center">Create Note</h1>
    <form action="{{ route('notes.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="ml-3">
            <label for="title" class="block text-gray-700 font-semibold ">Title</label>
            <input type="text" name="title" id="title" class="block p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
        </div>
        <div class="ml-3">
            <label for="body" class="block text-gray-700 font-semibold mb-2">Body</label>
            <textarea name="body" id="body" class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="w-20 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Save</button>
        </div>
    </form>
</div>
@endsection
