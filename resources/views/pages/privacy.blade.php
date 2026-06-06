@extends('layouts.app')

@section('title', 'Политика конфиденциальности')

@section('content')
    <div class="page-container">
        <h1>🔒 Политика конфиденциальности</h1>

        <div class="legal-content">
            <p class="last-updated">Последнее обновление: {{ date('d.m.Y') }}</p>

            <section>
                <h2>1. Какие данные мы собираем</h2>
                <p>1.1. При регистрации на Сайте мы собираем: ФИО, номер телефона, адрес электронной почты.</p>
                <p>1.2. При оформлении заказа: состав заказа, сумму, выбранный ПВЗ.</p>
                <p>1.3. Информация об использовании бонусов и истории заказов.</p>
            </section>

            <section>
                <h2>2. Как мы используем ваши данные</h2>
                <p>2.1. Для обработки и выполнения заказов.</p>
                <p>2.2. Для начисления и учета бонусов.</p>
                <p>2.3. Для связи с вами по вопросам заказов.</p>
                <p>2.4. Для улучшения качества обслуживания.</p>
            </section>

            <section>
                <h2>3. Защита данных</h2>
                <p>3.1. Мы используем современные методы шифрования для защиты ваших данных.</p>
                <p>3.2. Доступ к данным имеют только авторизованные сотрудники.</p>
                <p>3.3. Мы не передаем ваши данные третьим лицам без вашего согласия.</p>
            </section>

            <section>
                <h2>4. Ваши права</h2>
                <p>4.1. Вы можете запросить копию ваших данных.</p>
                <p>4.2. Вы можете потребовать удаления ваших данных из нашей системы.</p>
                <p>4.3. Вы можете отказаться от получения маркетинговых рассылок.</p>
            </section>

            <section>
                <h2>5. Контакты</h2>
                <p>По вопросам конфиденциальности обращайтесь:</p>
                <p>📞 Телефон: <strong>+7 (900) 923-59-09</strong></p>
                <p>✉️ Email: <strong>bezumno_shaurma@gmail.com</strong></p>
            </section>
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
            margin-bottom: 20px;
            color: #1a1a1a;
            font-size: 32px;
        }
        .last-updated {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .legal-content section {
            margin-bottom: 30px;
        }
        .legal-content h2 {
            color: #FF6B00;
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        .legal-content p {
            color: #555;
            line-height: 1.7;
            margin-bottom: 12px;
        }
    </style>
@endsection
