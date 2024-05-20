@extends('layouts.app')
@section('content')
<form action="{{route('quizzes.storeWithAI')}}" method="POST" id="createQuizForm" class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
    @csrf
    <div class="mb-4">
        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Tiêu đề Quiz:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $content ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>

    <div class="mb-4">
        <label for="size_questions" class="block text-gray-700 text-sm font-bold mb-2">Số lượng câu hỏi:</label>
        <input type="number" id="size_questions" name="size_questions" value="{{ old('size_questions', $size_questions ?? '') }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>

    <div class="mb-4">
        <label for="difficulty" class="block text-gray-700 text-sm font-bold mb-2">Độ khó:</label>
        <select id="difficulty" name="difficulty" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="dễ" @if(old('difficulty', $difficulty ?? '') === 'dễ') selected @endif>Dễ</option>
            <option value="trung bình" @if(old('difficulty', $difficulty ?? '') === 'trung bình') selected @endif>Trung bình</option>
            <option value="khó" @if(old('difficulty', $difficulty ?? '') === 'khó') selected @endif>Khó</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="language" class="block text-gray-700 text-sm font-bold mb-2">Ngôn ngữ:</label>
        <select id="language" name="language" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="Tiếng Việt" @if(old('language', $language ?? '') === 'Tiếng Việt') selected @endif>Tiếng Việt</option>
            <option value="Tiếng Anh" @if(old('language', $language ?? '') === 'Tiếng Anh') selected @endif>Tiếng Anh</option>
            <option value="Tiếng Nhật" @if(old('language', $language ?? '') === 'Tiếng Nhật') selected @endif>Tiếng Nhật</option>
            </select>
    </div>

    <div class="mb-4">
        <label for="types" class="block text-gray-700 text-sm font-bold mb-2">Loại câu hỏi:</label>
        @php
            $selectedTypes = old('types', explode(',', $types ?? ''));
        @endphp
        <div class="flex flex-wrap">
            <label class="inline-flex items-center mr-4">
                <input type="checkbox" name="types[]" value="radio" class="form-checkbox h-5 w-5 text-blue-600" @if(in_array('radio', $selectedTypes)) checked @endif>
                <span class="ml-2">Một lựa chọn</span>
            </label>
            <label class="inline-flex items-center mr-4">
                <input type="checkbox" name="types[]" value="checkbox" class="form-checkbox h-5 w-5 text-blue-600" @if(in_array('checkbox', $selectedTypes)) checked @endif>
                <span class="ml-2">Nhiều lựa chọn</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="types[]" value="text" class="form-checkbox h-5 w-5 text-blue-600" @if(in_array('text', $selectedTypes)) checked @endif>
                <span class="ml-2">Tự luận</span>
            </label>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Tạo Quiz
        </button>
    </div>
</form>

@endsection

@section('script')
@endsection