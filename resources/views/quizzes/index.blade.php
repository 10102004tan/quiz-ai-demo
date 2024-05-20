@extends('layouts.app')
@section('content')
    <a href="{{route("quizzes.create")}}">Tao quiz</a>
    <a href="{{route("quizzes.createWithAI")}}" class="p-2 bg-blue-500 round text-white">Tao quiz voi AI</a>
    <h1>Danh sách Quiz</h1>

    <div class="grid grid-cols-3 gap-4">
        @foreach($quizzes as $quiz)
            <div class="bg-white p-4 shadow-md">
                <h2 class="text-lg font-bold">{{ $quiz->title }}</h2>
                <p class="text-sm text-gray-500">{{ $quiz->description }}</p>
                <a href="{{ route('quizzes.questions.show',["quiz" => $quiz,'questionIndex'=>0]) }}" class="bg-blue-500 text-white px-4 py-2 mt-2 inline-block">Làm bài</a>
            </div>
        @endforeach
    </div>
@endsection
@section('script')
   <script>
     console.log('helloworld');
   </script>
@endsection