<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Xin chào {{ $user->name }}</h1>
    <p>Cảm ơn bạn đã đăng ký tại website chúng tôi. Vui lòng nhấn vào liên kết này để kích hoạt tài khoản</p>
    <a href="{{ url('/activate/'.$token) }}">Kích hoạt tài khoản</a>
    <p>Trân trọng</p>
</body>
</html>