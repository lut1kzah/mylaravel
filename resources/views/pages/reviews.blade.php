@extends('layouts.app')

@section('title', 'Отзывы клиентов')

@section('content')
    <div class="page-container">
        <h1>⭐ Отзывы наших клиентов</h1>

        <div class="reviews-stats">
            <div class="rating-circle">
                <span class="rating-number">4.8</span>
                <span class="rating-stars">★★★★★</span>
                <span class="rating-count">на основе {{ count($reviews) }} отзывов</span>
            </div>
            <a href="#" class="btn btn-add-review" onclick="showReviewForm()">✍️ Оставить отзыв</a>
        </div>

        <div class="reviews-list">
            @foreach($reviews as $review)
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                {{ substr($review['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="reviewer-name">{{ $review['name'] }}</div>
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review['rating'])
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="review-date">{{ date('d.m.Y', strtotime($review['date'] ?? 'now')) }}</div>
                    </div>
                    <div class="review-text">
                        <p>{{ $review['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Форма добавления отзыва (скрыта) -->
        <div id="reviewForm" style="display: none; margin-top: 30px; padding: 25px; background: #f9f9f9; border-radius: 15px;">
            <h3 style="margin-bottom: 20px;">✍️ Оставить отзыв</h3>
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <div class="form-group">
                    <label>Ваше имя *</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Оценка *</label>
                    <select name="rating" class="rating-select" required>
                        <option value="5">★★★★★ 5 - Отлично</option>
                        <option value="4">★★★★☆ 4 - Хорошо</option>
                        <option value="3">★★★☆☆ 3 - Средне</option>
                        <option value="2">★★☆☆☆ 2 - Плохо</option>
                        <option value="1">★☆☆☆☆ 1 - Ужасно</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Текст отзыва *</label>
                    <textarea name="text" rows="4" required placeholder="Поделитесь впечатлениями..."></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn">Отправить отзыв</button>
                    <button type="button" class="btn" style="background: #666;" onclick="hideReviewForm()">Отмена</button>
                </div>
            </form>
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
        .reviews-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .rating-circle {
            text-align: center;
        }
        .rating-number {
            font-size: 48px;
            font-weight: bold;
            color: #FF6B00;
            display: block;
        }
        .rating-stars {
            color: #FF6B00;
            font-size: 20px;
            margin: 5px 0;
        }
        .rating-count {
            font-size: 12px;
            color: #888;
        }
        .btn-add-review {
            background: #FF6B00;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
        }
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .review-card {
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 15px;
            transition: all 0.3s;
        }
        .review-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            background: #FF6B00;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
        }
        .reviewer-name {
            font-weight: bold;
            font-size: 16px;
        }
        .review-stars {
            color: #FF6B00;
            font-size: 14px;
            margin-top: 5px;
        }
        .review-date {
            font-size: 12px;
            color: #888;
        }
        .review-text p {
            color: #555;
            line-height: 1.6;
            margin: 0;
        }
        .rating-select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 100%;
        }
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
        }
        @media (max-width: 768px) {
            .page-container {
                padding: 20px;
            }
            .reviews-stats {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>

    <script>
        function showReviewForm() {
            document.getElementById('reviewForm').style.display = 'block';
        }
        function hideReviewForm() {
            document.getElementById('reviewForm').style.display = 'none';
        }
    </script>
@endsection
