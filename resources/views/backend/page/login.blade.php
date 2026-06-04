<!DOCTYPE html>
<html lang="th">
<head>
    @include("$prefix.layout.header")
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
