<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <main class="admin-page">
        <section class="admin-topbar">
            <div>
                <p class="admin-kicker">Ништяк Бодрит</p>
                <h1>Админ панель</h1>
            </div>

            <div class="admin-topbar__actions">
                <a href="{{ route('admin.drinks.create') }}" class="admin-button">Добавить товар</a>
                <a href="{{ route('admin.orders.index') }}" class="admin-button admin-button--ghost">Заказы</a>
                <a href="{{ route('index') }}" class="admin-button admin-button--ghost">На сайт</a>
            </div>
        </section>

        @if (session('admin_message'))
            <div class="admin-notice">{{ session('admin_message') }}</div>
        @endif

        <section class="admin-card">
            <div class="admin-card__head">
                <h2>Все товары</h2>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Раздел</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Статус</th>
                            <th>Сортировка</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drinks as $drink)
                            <tr>
                                <td>{{ $drink->name }}</td>
                                <td>{{ $drink->catalog === 'home' ? 'Домой' : 'Меню' }}</td>
                                <td>{{ $drink->category_name }}</td>
                                <td>{{ number_format($drink->price, 0, ',', ' ') }} ₽</td>
                                <td>{{ $drink->is_active ? 'Активен' : 'Скрыт' }}</td>
                                <td>{{ $drink->sort_order }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.drinks.edit', $drink) }}" class="admin-link">Редактировать</a>
                                        <form action="{{ route('admin.drinks.destroy', $drink) }}" method="POST"
                                            onsubmit="return confirm('Удалить товар {{ $drink->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-link admin-link--danger">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>
