<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập | Quản Gia 5.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
        :root { --primary: #4e73df; }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 20px;

            /* --- THÊM HÌNH NỀN Ở ĐÂY --- */
            background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            /* --------------------------- */
        }

        /* Lớp phủ màu đen mờ để form nổi bật hơn */
        body::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Độ tối 50% */
            z-index: -1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95); /* Màu trắng hơi trong suốt nhẹ */
            width: 100%;
            max-width: 500px;
            padding: 50px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3); /* Bóng đổ đậm hơn */
            backdrop-filter: blur(5px);
        }

        .brand-title { font-weight: 800; font-size: 2.2rem; color: var(--primary); margin-bottom: 10px; }

        .input-group-text {
            background: #fff; border: 1px solid #ddd; border-right: none;
            border-radius: 50px 0 0 50px; padding-left: 25px; color: #4e73df; font-size: 1.2rem;
        }
        .form-control {
            background: #fff; border: 1px solid #ddd; border-left: none;
            border-radius: 0 50px 50px 0;
            height: 60px;
            font-size: 1.1rem;
            padding-left: 15px;
        }
        .form-control:focus { background: #fff; box-shadow: none; border-color: var(--primary); }
        .form-control:focus + .input-group-prepend .input-group-text { background: #fff; border-color: var(--primary); }

        .btn-auth {
            width: 100%; border-radius: 50px;
            padding: 15px;
            font-size: 1.3rem; font-weight: 700;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none; color: white;
            box-shadow: 0 10px 25px rgba(78, 115, 223, 0.4);
            transition: 0.3s;
            letter-spacing: 1px;
        }
        .btn-auth:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(78, 115, 223, 0.5); }

        .switch-link { text-align: center; margin-top: 25px; font-size: 1.1rem; }
        .switch-link a { font-weight: 700; color: var(--primary); text-decoration: none; }
        .switch-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="text-center mb-5">
        <i class="fa fa-laptop-house fa-4x text-primary mb-3"></i>
        <h3 class="brand-title">QUẢN GIA <span style="color: #f6c23e;">5.0</span></h3>
        <p class="text-muted" style="font-size: 1.1rem;">Chào mừng bạn quay trở lại!</p>
    </div>

    <form id="login-form">
        <div class="input-group mb-4">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
            <input type="email" name="email" class="form-control" placeholder="Nhập Email..." required>
        </div>

        <div class="input-group mb-5">
            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock"></i></span></div>
            <input type="password" name="password" class="form-control" placeholder="Nhập Mật khẩu..." required>
        </div>

        <button class="btn btn-auth">ĐĂNG NHẬP</button>
    </form>

    <div class="switch-link">
        Chưa có tài khoản? <a href="signup.php">Đăng ký ngay</a>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="text-secondary font-weight-bold"><i class="fa fa-arrow-left mr-1"></i> Về trang chủ</a>
    </div>
</div>

<script>
    $('#login-form').submit(function(e){
        e.preventDefault();
        var btn = $(this).find('button');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');

        $.ajax({
            url:'../ajax.php?action=login_customer',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp==1) {
                    location.href ='index.php';
                } else {
                    alert("⚠️ Email hoặc mật khẩu không chính xác!");
                    btn.prop('disabled', false).html('ĐĂNG NHẬP');
                }
            }
        })
    })
</script>
</body>
</html>