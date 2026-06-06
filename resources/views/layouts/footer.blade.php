<footer style="background: #1a1a1a; color: #ccc; padding: 50px 0 25px; margin-top: 60px; border-top: 4px solid #FF6B00;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 30px;">

        <!-- Основная сетка -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; margin-bottom: 40px;">

            <!-- Блок О нас -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 24px;">🍟</span>
                    <h3 style="color: #FF6B00; margin: 0; font-size: 18px;">О компании</h3>
                </div>
                <p style="line-height: 1.6; font-size: 14px; color: #ccc; margin: 0;">
                    Безумно — это сеть быстрого питания с авторскими рецептами шаурмы.
                    Готовим только из свежих продуктов с любовью к каждому клиенту.
                </p>
            </div>

            <!-- Блок Контакты -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 24px;">📞</span>
                    <h3 style="color: #FF6B00; margin: 0; font-size: 18px;">Контакты</h3>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="tel:+79009235909" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>📱</span> +7 (900) 923-59-09
                    </a>
                    <a href="mailto:bezumno_shaurma@gmail.com" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>✉️</span> bezumno_shaurma@gmail.com
                    </a>
                    <a href="https://vk.com/bezumno_shaurma" target="_blank" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>🔗</span> ВКонтакте
                    </a>
                </div>
            </div>

            <!-- Блок Информация -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <span style="font-size: 24px;">📋</span>
                    <h3 style="color: #FF6B00; margin: 0; font-size: 18px;">Информация</h3>
                </div>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="{{ route('faq') }}" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>❓</span> Часто задаваемые вопросы
                    </a>
                    <a href="{{ route('reviews') }}" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>⭐</span> Отзывы клиентов
                    </a>
                    <a href="{{ route('terms') }}" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>📜</span> Пользовательское соглашение
                    </a>
                    <a href="{{ route('privacy') }}" style="color: #ccc; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 10px;">
                        <span>🔒</span> Политика конфиденциальности
                    </a>
                </div>
            </div>
        </div>

        <!-- Копирайт -->
        <div style="text-align: center; padding-top: 25px; border-top: 1px solid #333; font-size: 12px; color: #666;">
            © {{ date('Y') }} Безумно. Все права защищены.
        </div>
    </div>
</footer>

<style>
    footer a:hover {
        color: #FF6B00 !important;
    }
</style>
