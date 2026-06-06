@extends('layouts.app')

@section('title', 'Часто задаваемые вопросы')

@section('content')
    <div class="page-container">
        <h1>❓ Часто задаваемые вопросы</h1>

        <div class="faq-list">
            @foreach($faqs as $index => $faq)
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleAnswer({{ $index }})">
                        <span class="question-text">{{ $faq['question'] }}</span>
                        <span class="question-icon">▼</span>
                    </div>
                    <div class="faq-answer" id="answer-{{ $index }}" style="display: none;">
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .page-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .page-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #1a1a1a;
            font-size: 32px;
        }
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .faq-item {
            background: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            cursor: pointer;
            background: #f9f9f9;
            transition: background 0.3s;
            font-weight: bold;
            font-size: 16px;
        }
        .faq-question:hover {
            background: #f0f0f0;
        }
        .question-text {
            color: #1a1a1a;
        }
        .question-icon {
            color: #FF6B00;
            transition: transform 0.3s;
            font-size: 14px;
        }
        .faq-item.active .question-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            padding: 0 20px;
            background: white;
            border-top: 1px solid #eee;
        }
        .faq-answer p {
            padding: 20px 0;
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
    </style>

    <script>
        function toggleAnswer(index) {
            const answer = document.getElementById('answer-' + index);
            const item = answer.closest('.faq-item');

            if (answer.style.display === 'none' || answer.style.display === '') {
                answer.style.display = 'block';
                item.classList.add('active');
            } else {
                answer.style.display = 'none';
                item.classList.remove('active');
            }
        }
    </script>
@endsection
