<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <main class="admin-page">
        <section class="admin-topbar">
            <div>
                <p class="admin-kicker">Ништяк Бодрит</p>
                <h1>{{ $pageTitle }}</h1>
            </div>

            <div class="admin-topbar__actions">
                <a href="{{ route('admin.orders.index') }}" class="admin-button admin-button--ghost">Заказы</a>
                <a href="{{ route('admin.drinks.index') }}" class="admin-button admin-button--ghost">Назад</a>
                @if ($drink->exists)
                    <form action="{{ route('admin.drinks.destroy', $drink) }}" method="POST"
                        onsubmit="return confirm('Удалить товар {{ $drink->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button admin-button--danger">Удалить</button>
                    </form>
                @endif
            </div>
        </section>

        <section class="admin-card">
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                @if ($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                <div class="admin-grid">
                    <label class="admin-field">
                        <span>Название</span>
                        <input type="text" name="name" value="{{ old('name', $drink->name) }}" required>
                    </label>

                    <label class="admin-field">
                        <span>Раздел</span>
                        <select name="catalog" required>
                            @foreach ($catalogOptions as $catalogKey => $catalogName)
                                <option value="{{ $catalogKey }}"
                                    {{ old('catalog', $drink->catalog) === $catalogKey ? 'selected' : '' }}>
                                    {{ $catalogName }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="admin-field">
                        <span>Категория</span>
                        <select name="category_key" {{ $categories === [] ? 'disabled' : 'required' }}>
                            @if ($categories === [])
                                <option value="">Нет доступных категорий</option>
                            @else
                                @foreach ($categories as $category)
                                    <option value="{{ $category['key'] }}"
                                        {{ old('category_key', $drink->category_key) === $category['key'] ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if ($categories === [])
                            <small>Сначала нужен хотя бы один товар с категорией, чтобы можно было выбирать из списка.</small>
                        @endif
                    </label>

                    <label class="admin-field">
                        <span>Базовая цена</span>
                        <input type="number" name="price" value="{{ old('price', $drink->price) }}" min="0" required>
                    </label>

                    <label class="admin-field">
                        <span>Диапазон размеров</span>
                        <input type="text" name="volume" value="{{ old('volume', $drink->volume) }}">
                    </label>

                    <label class="admin-field">
                        <span>Фото товара</span>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <small>Поддерживаются JPG, PNG и WEBP до 5 МБ.</small>
                    </label>

                    <label class="admin-field">
                        <span>Сортировка</span>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $drink->sort_order) }}" min="0" required>
                    </label>

                    <label class="admin-field admin-field--checkbox">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $drink->is_active) ? 'checked' : '' }}>
                        <span>Активный товар</span>
                    </label>
                </div>

                <label class="admin-field">
                    <span>Описание</span>
                    <textarea name="description" rows="4">{{ old('description', $drink->description) }}</textarea>
                </label>

                <label class="admin-field">
                    <span>Состав</span>
                    <textarea name="ingredients" rows="4">{{ old('ingredients', $drink->ingredients) }}</textarea>
                </label>

                <label class="admin-field">
                    <span>Размеры и цены</span>
                    <textarea name="size_options" rows="6" placeholder="S: 220&#10;M: 250&#10;L: 280">{{ old('size_options', $sizeOptionsText) }}</textarea>
                    <small>Каждый размер с новой строки в формате `Размер: цена`.</small>
                </label>

                @if ($drink->image_path)
                    <div class="admin-field">
                        <span>Текущее фото</span>
                        <div class="admin-image-preview">
                            <img src="{{ asset($drink->image_path) }}" alt="{{ $drink->name }}">
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-errors">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="admin-button">Сохранить</button>
            </form>
        </section>
    </main>
</body>

</html>
