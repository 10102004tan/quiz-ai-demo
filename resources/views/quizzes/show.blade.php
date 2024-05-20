@extends('layouts.app')
@section('content')
<h1>Choi game</h1>
<form id="submitQuestion" class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
    <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
    <input type="hidden" name="question_id" value="{{ $question->id }}"> 
    <div id="questionContent" class="mb-4">
        {!! $question->excerpt !!}  {{-- Hiển thị nội dung câu hỏi --}}
    </div>

    <div id="answerOptions" class="mb-4">
        @if ($question->type === 'radio')
            @foreach ($question->answers as $answer)
                <div>
                    <input type="radio" name="answer" value="{{ $answer->id }}" id="answer_{{ $answer->id }}">
                    <label for="answer_{{ $answer->id }}">{{ $answer->content }}</label>
                </div>
            @endforeach
        @elseif ($question->type === 'checkbox')
            @foreach ($question->answers as $answer)
                <div>
                    <input type="checkbox" name="answer[]" value="{{ $answer->id }}" id="answer_{{ $answer->id }}">
                    <label for="answer_{{ $answer->id }}">{{ $answer->content }}</label>
                </div>
            @endforeach
        @elseif ($question->type === 'text')
            <textarea name="answer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
        @endif
    </div>

    <div class="flex items-center justify-between">
        <button type="button" id="prevQuestionBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                @if ($currentQuestionIndex === 0) disabled @endif>
            Câu trước
        </button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            @if ($currentQuestionIndex === $totalQuestions - 1)
                Hoàn thành
            @else
                Câu tiếp
            @endif
        </button>
    </div>
</form>
@endsection

@section('script')
@endsection