<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <title>Регистрация</title>
</head>

<body>
    <main>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            @if (!empty($redirectTo))
                <input type="hidden" name="redirect" value="{{ $redirectTo }}">
            @endif
            <h1 class="login-title">РЕГИСТРАЦИЯ</h1>
            <div class="form-group {{ $errors->has('name') ? 'error' : '' }}">
                <input type="name" name="name" value="{{ old('name') }}" placeholder="имя" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group {{ $errors->has('email') ? 'error' : '' }} ">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group {{ $errors->has('password') ? 'error' : '' }}">
                <input type="password" name="password" placeholder="пароль" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-auth">регистрация</button>
            <a href="{{ route('login', $redirectTo ? ['redirect' => $redirectTo] : []) }}">есть аккаунт?</a>
        </form>
    </main>
</body>

</html>
