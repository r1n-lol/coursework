<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Общие стили --}}
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    {{-- Стили для конкретной стр --}}
    @if (!empty($pageCss))
        <link rel="stylesheet" href="{{ asset('css/' . $pageCss . '.css') }}">
    @endif

    {{-- Заголовок --}}
    <title> {{ $title ?? 'Ошибка' }} </title>
</head>

<body>
    {{-- Шапка --}}
    @include('components.header')

    {{-- Основной контент каждой стр --}}
   
        @yield('content')
    <main>
        @include('components.footer')
    </main>
</body>

</html>
