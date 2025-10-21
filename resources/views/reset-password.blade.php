<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fs-5">
                    🔒 Đặt lại mật khẩu
                </div>
                <div class="card-body p-4">

                    <!-- Form reset password -->
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf <!-- token chống CSRF của Laravel -->
                        
                        <!-- Hidden reset token -->
                        <input type="hidden" name="token" value="{{ $token ?? '' }}">

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                name="email" 
                                placeholder="Nhập email của bạn" 
                                required
                                value="{{ old('email') }}"
                            >
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Nhập mật khẩu mới" 
                                required
                            >
                        </div>

                        <!-- Confirm password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Nhập lại mật khẩu mới" 
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-3">
                            Cập nhật mật khẩu
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
