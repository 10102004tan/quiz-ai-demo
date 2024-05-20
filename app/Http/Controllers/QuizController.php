<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\UserAnswer;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{

    public function index()
    {
        $quizzes = Quiz::all();
        return view('quizzes.index', ['quizzes' => $quizzes]);
    }

    public function create()
    {
        // Tạo 1 quiz mới
        $quiz = Quiz::create([
            'title' => 'Quiz ví dụ',
            'description' => 'Đây là quiz ví dụ',
        ]);
        return view('quizzes.create', ['id' => $quiz->id]);
    }

    public function show(Quiz $quiz)
    {
        return view('quizzes.show', ['quiz' => $quiz]);
    }

    public function store(Request $request)
    {
        // Validate dữ liệu từ request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // ... các trường khác
        ]);

        // Tạo quiz mới
        $quiz = Quiz::create($validatedData);

        // Trả về phản hồi JSON
        return response()->json([
            'message' => 'Quiz đã được tạo thành công!',
            'quiz' => $quiz, // Có thể trả về thông tin quiz mới để cập nhật giao diện
        ]);
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        return view('quizzes.edit', ['quiz' => $quiz]);
    }

    public function update(Request $request, Quiz $quiz)
    {
        //validate
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // ... các trường khác
        ]);

        $quiz->update($validatedData);
        // return response()->json([
        //     'message' => 'Quiz đã được cập nhật thành công!',
        //     'quiz' => $quiz, // Có thể trả về thông tin quiz mới để cập nhật giao diện
        // ]);
        return redirect()->route('quizzes.index');
    }


    //create new question
    public function storeQuestion(Request $request) // Dependency Injection
    {
        // Validate dữ liệu từ request
        $validatedData = $request->validate([
            'excerpt' => 'required|string',
            'type' => 'required|in:radio,checkbox,text', // Kiểm tra loại câu hỏi
            // ... các trường khác
        ]);

        // // Tạo câu hỏi mới
        $quiz = Quiz::findOrFail($request->input('quiz_id'));
        $question = $quiz->questions()->create($validatedData);

        // // Xử lý các đáp án (tùy thuộc vào loại câu hỏi)
        if ($validatedData['type'] === 'radio' || $validatedData['type'] === 'checkbox') {
            $answersData = $request->input('answers'); // Lấy dữ liệu các đáp án
            foreach ($answersData as $answerData) {
                $question->answers()->create([
                    'content' => $answerData['content'],
                    'is_correct' => isset($answerData['is_correct']) ? true : false,
                ]);
            }
        }

        // // Trả về phản hồi JSON
        return response()->json([
            'message' => 'Câu hỏi đã được tạo thành công!',
            'question' => $question, // Có thể trả về thông tin câu hỏi mới để cập nhật giao diện
        ]);
       
    }

    //ham choi
    public function submitAnswer(Request $request, $quizId, $questionId)
    {
        //$userId = auth()->id(); // Lấy ID người dùng nếu đã đăng nhập
        $userId = 1;
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $result = Result::create([
            'user_id' => $userId,
            'quiz_id' => $quizId,
        ]);

        $score = 0;
        $correctAnswerIds = $question->answers()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();
        $userAnswerIds = array_map('intval', $request->input('answer', []));
        $isCorrect = ($correctAnswerIds == $userAnswerIds);



        UserAnswer::create([
            'result_id' => $result->id,
            'question_id' => $questionId,
            'answer_id' => json_encode($correctAnswerIds),
            'is_correct' => $isCorrect,
        ]);

        if ($isCorrect) {
            $score++;
        }

        $result->score = $score;
        $result->save();

        return [
            'isCorrect' => $isCorrect,
            'score' => $score,
        ];
    }


    //lay toan bo questions cua 1 quiz
    public function getQuestions($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $questions = $quiz->questions;
        return response()->json([
            'questions' => $questions,
        ]);
    }

    public function showQuestion(Quiz $quiz, $questionIndex)
    {
        $questions = $quiz->questions;
        $question = $questions[$questionIndex];

        return view('quizzes.showQuestion', [
            'quiz' => $quiz,
            'question' => $question,
            'currentQuestionIndex' => $questionIndex,
            'totalQuestions' => $questions->count(),
        ]);
    }


    //create quiz with ai
    public function createJsonQuizWithAI(Request $request)
    {
        // dd($request->all());
        $difficulty = $request->difficulty;
        $size_questions = $request->size_questions;
        $content = $request->title;
        $language = $request->language;
        $typesArr = $request->types;
        $types = implode(',', $typesArr);
        $prompt = '
        hãy cho tôi ngẫu nhiên <<size_questions : '.$size_questions.'>> 
        câu hỏi kiểu <<type: ['.$types.']>> 
        về 1 chủ đề ngẫu nhiên trong <<title: 
        ]'.$content.'>>. 
        tôi muốn các câu hỏi có mức độ khó 
        là <<difficulty : '.$difficulty.'>>.
        Tôi muốn ngôn ngữ cho bộ câu hỏi là 
        <<language : '.$language.'>>. 
        Tôi muốn question phải có ít nhất 4 answer.
        Tôi muốn question gồm : excerpt,type và answers. 
        Tôi muốn answer gồm : content, is_correct. tôi muốn kết quả trả 
        về là 1 mảng các quesions.Tôi muốn các giá trị trong 
        <<giá trị>> phải hợp lệ, nếu không hợp lệ phải trả về mảng questions [].
        Tôi muốn xử lý các ký tự không hợp lệ trong kiểu dữ liệu json.
        Tôi muốn kết quả trả phải duy nhất là kiểu dữ liệu json bắt đầu { và kết thúc là }.
        Đây là ví dụ kết hợp lệ : 
        {
            "questions": [
                {
                    "excerpt": "Câu hỏi 1",
                    "type": "radio",
                    "answers": [
                        {
                            "content": "Đáp án 1",
                            "is_correct": true
                        },
                        {
                            "content": "Đáp án 2",
                            "is_correct": false
                        }
                    ]
                }
               
            ]
        }.

        Đây là trường hợp không hợp lệ : ```json{
            "questions": {}
        }``` bởi vì không bắt đầu bằng { và kết thúc bằng }.
        ';
        $result = Gemini::geminiPro()->generateContent($prompt);
        $result = str_replace('`json','', $result->text());
        $result = str_replace('`','', $result);
        $fileName = $this->hashFileName($content);
        Storage::disk('public')->put("datajson/$fileName", $result);
        $data = Storage::disk('public')->get("datajson/$fileName");
        //delete file after read
        return json_decode($data, true);
    }



    function hashFileName($fileName)
    {
        $hashedName = md5($fileName . time()); // Thêm thời gian để tránh trùng lặp
        return "{$hashedName}.json";
    }

    public function storeQuizWithAI(Request $request)
    {
        $data = $this->createJsonQuizWithAI($request);
       

        if (isset($data['questions']) && count($data['questions']) > 0){
            $quiz = Quiz::create([
                'title' => 'Quiz with AI',
                'description' => 'Hello world',
            
            ]);
            foreach ($data['questions'] as $question) {
                $newQuestion = $quiz->questions()->create([
                    'excerpt' => $question['excerpt'],
                    'type' => $question['type'],
                ]);
                foreach ($question['answers'] as $answer) {
                    $newQuestion->answers()->create([
                        'content' => $answer['content'],
                        'is_correct' => $answer['is_correct'],
                    ]);
                }
            }
            return view('quizzes.edit', ['quiz' => $quiz]);
        }
        else{
            return response()->json([
                'message' => 'Không tạo được câu hỏi',
            ]);
        }
    }

    public function createQuizWithAI(){
        return view('quizzes.create-ai');
    }
}
