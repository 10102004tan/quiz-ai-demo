@extends('layouts.app')
@section('content')
<h1>Taọ quiz</h1>
<a href="{{route('quizzes.edit',['id'=>$id])}}" class="p-3 rounded bg-slate-500 text-white">Hoan thanh</a>
<form id="createQuestionForm" class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
    <input type="hidden" name="quiz_id" value="{{$id }}">

    <div class="mb-4">
        <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">Nội dung câu hỏi:</label>
        <textarea id="excerpt" name="excerpt" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" required></textarea>
    </div>

    <div class="mb-4">
        <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Loại câu hỏi:</label>
        <select id="type" name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="radio">Một lựa chọn</option>
            <option value="checkbox">Nhiều lựa chọn</option>
            <option value="text">Tự luận</option>
        </select>
    </div>

    <div id="answerOptions" class="mb-4">
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Tạo câu hỏi
        </button>
    </div>
</form>

@endsection
@section('script')
<script>
    const typeSelect = document.getElementById('type');
    const answerOptionsContainer = document.getElementById('answerOptions');
    const createQuestionForm =document.getElementById('createQuestionForm');

    typeSelect.addEventListener('change', () => {
        const selectedType = typeSelect.value;
        answerOptionsContainer.innerHTML = ''; // Xóa các lựa chọn cũ

        if (selectedType !== 'text') {
            for (let i = 0; i < 4; i++) { // Tạo 4 lựa chọn cho radio/checkbox
                const answerDiv = document.createElement('div');
                answerDiv.classList.add('mb-2');
                answerDiv.innerHTML = `
                    <input type="text" name="answers[${i}][content]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Đáp án ${i + 1}" required>
                    <input type="checkbox" name="answers[${i}][is_correct]" class="form-checkbox h-5 w-5 text-blue-600"> Đúng
                `;
                answerOptionsContainer.appendChild(answerDiv);
            }
        }
    });


    //ajax
    createQuestionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(createQuestionForm);
        console.log(Object.fromEntries(formData));
        try {
                // Send AJAX POST request using Axios
                const response = await axios.post("{{route('quizzes.questions.store')}}", formData);
                const result = response.data;
                console.log(result);
                createQuestionForm.reset();
            } catch (error) {
                console.error('Error:', error);
            }
    });

</script>
@endsection