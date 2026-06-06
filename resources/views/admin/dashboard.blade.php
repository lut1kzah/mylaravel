@extends('admin.layouts.admin')

@section('content')
    <div>
        <h1>📊 Статистика и отчёты</h1>

        <!-- Фильтр по периоду -->
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 25px;">
            <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-size: 13px; color: #666;">Период</label>
                    <select name="period" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Сегодня</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Последние 7 дней</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Этот месяц</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Этот год</option>
                    </select>
                </div>
                <button type="submit" class="btn" style="padding: 10px 25px;">📅 Применить</button>
            </form>
        </div>

        <!-- Ключевые метрики -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 32px; color: #FF6B00; font-weight: bold;">{{ number_format($totalRevenue, 0, ',', ' ') }} ₽</div>
                <div style="color: #666;">Общая выручка</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 32px; color: #FF6B00; font-weight: bold;">{{ $totalOrders }}</div>
                <div style="color: #666;">Всего заказов</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 32px; color: #FF6B00; font-weight: bold;">{{ $totalUsers }}</div>
                <div style="color: #666;">Пользователей</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 32px; color: #FF6B00; font-weight: bold;">{{ number_format($bonusStats['active_balance'], 0, ',', ' ') }}</div>
                <div style="color: #666;">Активных бонусов</div>
            </div>
        </div>

        <!-- Графики (простые, без лишних библиотек) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <!-- График заказов -->
            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>📈 Заказы за последние 7 дней</h3>
                <div style="display: flex; align-items: flex-end; gap: 8px; height: 200px; margin-top: 20px;">
                    @foreach($chartData['orders'] as $index => $count)
                        @php
                            $maxOrders = max($chartData['orders']->toArray()) ?: 1;
                            $height = max(10, ($count / $maxOrders) * 150);
                        @endphp
                        <div style="flex: 1; text-align: center;">
                            <div style="background: #FF6B00; height: {{ $height }}px; border-radius: 8px; transition: all 0.3s;"></div>
                            <div style="font-size: 11px; margin-top: 8px; color: #666;">{{ substr($chartData['dates'][$index], 5, 5) }}</div>
                            <div style="font-size: 12px; font-weight: bold;">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Статусы заказов -->
            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>📊 Статусы заказов</h3>
                <div style="margin-top: 15px;">
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-block; width: 100px;">🆕 Новые:</span>
                        <strong>{{ $orderStatuses['NEW'] }}</strong>
                        <div style="background: #e0e0e0; height: 8px; border-radius: 4px; margin-top: 5px; width: 100%;">
                            <div style="background: #2196F3; width: {{ $totalOrders ? ($orderStatuses['NEW'] / $totalOrders * 100) : 0 }}%; height: 8px; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-block; width: 100px;">🍳 Готовятся:</span>
                        <strong>{{ $orderStatuses['COOKING'] }}</strong>
                        <div style="background: #e0e0e0; height: 8px; border-radius: 4px; margin-top: 5px;">
                            <div style="background: #ff9800; width: {{ $totalOrders ? ($orderStatuses['COOKING'] / $totalOrders * 100) : 0 }}%; height: 8px; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-block; width: 100px;">✅ Выданы:</span>
                        <strong>{{ $orderStatuses['DELIVERED'] }}</strong>
                        <div style="background: #e0e0e0; height: 8px; border-radius: 4px; margin-top: 5px;">
                            <div style="background: #4caf50; width: {{ $totalOrders ? ($orderStatuses['DELIVERED'] / $totalOrders * 100) : 0 }}%; height: 8px; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-block; width: 100px;">❌ Отменены:</span>
                        <strong>{{ $orderStatuses['CANCELLED'] }}</strong>
                        <div style="background: #e0e0e0; height: 8px; border-radius: 4px; margin-top: 5px;">
                            <div style="background: #e74c3c; width: {{ $totalOrders ? ($orderStatuses['CANCELLED'] / $totalOrders * 100) : 0 }}%; height: 8px; border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Популярные товары и бонусы -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>🔥 Популярные товары</h3>
                @forelse($popularProducts as $product)
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                        <span>{{ $product->name }}</span>
                        <span style="color: #FF6B00; font-weight: bold;">{{ $product->total_sold }} шт.</span>
                    </div>
                @empty
                    <p style="color: #666; text-align: center; padding: 20px;">Нет данных</p>
                @endforelse
            </div>

            <div style="background: white; padding: 20px; border-radius: 15px;">
                <h3>🎁 Бонусная статистика</h3>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <span>Начислено бонусов:</span>
                    <span style="color: #27ae60; font-weight: bold;">+{{ number_format($bonusStats['earned'], 0, ',', ' ') }} ₽</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <span>Списано бонусов:</span>
                    <span style="color: #e74c3c; font-weight: bold;">-{{ number_format($bonusStats['spent'], 0, ',', ' ') }} ₽</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <span>Активный баланс:</span>
                    <span style="color: #FF6B00; font-weight: bold;">{{ number_format($bonusStats['active_balance'], 0, ',', ' ') }} ₽</span>
                </div>
            </div>
        </div>

        <!-- Заказы по дням -->
        <div style="background: white; padding: 20px; border-radius: 15px;">
            <h3>📋 Заказы за период</h3>
            <table class="admin-table">
                <thead>
                <tr><th>Дата</th><th>Количество заказов</th><th>Выручка</th></tr>
                </thead>
                <tbody>
                @forelse($ordersByDay as $day)
                    <tr>
                        <td>{{ date('d.m.Y', strtotime($day->date)) }}</td>
                        <td>{{ $day->count }}</td>
                        <td style="color: #FF6B00; font-weight: bold;">{{ number_format($day->revenue, 0, ',', ' ') }} ₽</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center;">Нет заказов за выбранный период</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
