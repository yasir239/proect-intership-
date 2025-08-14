<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #f7fafc; display: flex; align-items: center; justify-content: center; height: 100vh; }
        form { background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 2px 8px #0001; min-width: 320px; }
        label { display: block; margin-bottom: 0.5rem; color: #333; }
        input { width: 100%; padding: 0.5rem; margin-bottom: 1rem; border-radius: 0.5rem; border: 1px solid #ccc; }
        button { width: 100%; padding: 0.7rem; background: #2563eb; color: #fff; border: none; border-radius: 0.5rem; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { color: #e11d48; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>
    <form method="POST" action="/login">
        @csrf
        <label>اسم المستخدم</label>
        <input type="text" name="username" required>
        <label>كلمة المرور</label>
        <input type="password" name="password" required>
        <button type="submit">دخول</button>
        @error('message')
            <div class="error">{{ $message }}</div>
        @enderror
    </form>
</body>
</html>
