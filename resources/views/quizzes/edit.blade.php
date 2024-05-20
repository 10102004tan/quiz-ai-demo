@extends('layouts.app')
@section('content')
    <h1>Hoan thanh</h1>
    <form action="{{ route('quizzes.update', ['quiz' => $quiz]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ $quiz->title }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $quiz->description }}</textarea>
        </div>
        //Test
        <h2 class="text-lg font-bold">Danh sách câu hỏi</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($quiz->questions as $question)
                <div class="bg-white p-4 shadow-md">
                    <h2 class="text-lg font-bold">{{ $question->excerpt }}</h2>
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Published</button>
        </div>
    </form>

@endsection
@section('script')
   <script>
   </script>
@endsection