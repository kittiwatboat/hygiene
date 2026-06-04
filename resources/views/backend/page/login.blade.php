<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 32px;
        }
        .login-card h1 {
            margin: 0 0 20px;
            font-size: 28px;
            color: #111827;
        }
        .login-card label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
        }
        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .login-card button {
            width: 100%;
            padding: 12px 14px;
            background: #2563eb;
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        .login-card button:hover {
            background: #1d4ed8;
        }
        .login-card .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .login-card .actions label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }
        .login-card .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>เข้าสู่ระบบ</h1>
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">อีเมล</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">รหัสผ่าน</label>
            <input id="password" type="password" name="password" required>

            <div class="actions">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    จำฉันไว้
                </label>
            </div>

            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>
