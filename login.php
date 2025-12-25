<!DOCTYPE html>
<html lang="vi">
<?php
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
    $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Đăng nhập | <?php echo $_SESSION['system']['name'] ?></title>

    <?php include('./header.php'); ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <?php
    if(isset($_SESSION['login_id']))
        header("location:index.php?page=home");
    ?>

</head>
<style>
    /* --- CẤU HÌNH CHUNG --- */
    body {
        width: 100%;
        height: 100vh;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f4f7f6;
        overflow: hidden;
    }
    main#main {
        width: 100%;
        height: 100%;
        display: flex;
    }

    /* --- CỘT TRÁI --- */
    #login-left {
        width: 60%;
        height: 100%;
        background: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') center center no-repeat;
        background-size: cover;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
    }

    #login-left::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(0, 83, 176, 0.85) 0%, rgba(0, 150, 255, 0.7) 100%);
        z-index: 1;
    }

    .brand-content {
        z-index: 2;
        text-align: center;
        padding: 40px;
        animation: fadeInUp 1s ease-out;
    }

    .brand-logo {
        font-size: 5rem;
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.2);
        width: 120px;
        height: 120px;
        line-height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px auto;
        backdrop-filter: blur(5px);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        display: flex;            /* Căn giữa icon logo */
        align-items: center;
        justify-content: center;
    }

    #login-left h1 {
        font-weight: 700;
        font-size: 3rem;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* --- CỘT PHẢI --- */
    #login-right {
        width: 40%;
        height: 100%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 85%;
        max-width: 420px;
        padding: 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        animation: fadeInUp 1.2s ease-out;
    }

    .login-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        text-align: center;
    }

    .login-subtitle {
        text-align: center;
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    /* --- SỬA LỖI ICON Ở ĐÂY --- */
    .input-group-custom {
        position: relative !important; /* Ép buộc vị trí tương đối */
        margin-bottom: 25px;
        width: 100%;
    }

    /* Class riêng cho icon để không bị trùng */
    .custom-icon {
        position: absolute !important;
        left: 20px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #aaa;
        font-size: 1.1rem;
        z-index: 10; /* Đảm bảo icon nổi lên trên */
        pointer-events: none; /* Click xuyên qua icon vào ô input */
        transition: 0.3s;
    }

    .form-control-custom {
        width: 100%;
        padding: 15px 15px 15px 55px; /* Chừa chỗ cho icon */
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        background: #fcfcfc;
        font-size: 1rem;
        outline: none;
        color: #333;
        box-sizing: border-box; /* Quan trọng để không vỡ khung */
    }

    /* Hiệu ứng Focus */
    .form-control-custom:focus {
        border-color: #007bff;
        background: #fff;
        box-shadow: 0 5px 20px rgba(0, 123, 255, 0.1);
    }

    /* Đổi màu icon khi focus input ngay sau nó */
    .form-control-custom:focus + .custom-icon {
        color: #007bff;
    }

    /* --- FIX LỖI MÀU NỀN XANH CỦA CHROME AUTOFILL --- */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px white inset !important; /* Đè màu trắng lên màu xanh */
        -webkit-text-fill-color: #333 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .btn-login {
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px; /* Khoảng cách giữa chữ và mũi tên */
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 123, 255, 0.4);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 900px) {
        #login-left { display: none; }
        #login-right { width: 100%; }
    }
</style>

<body>

<main id="main">
    <div id="login-left">
        <div class="brand-content">
            <div class="brand-logo">
                <i class="fa-solid fa-building"></i> </div>
            <h1><?php echo $_SESSION['system']['name'] ?></h1>
            <p>Quản lý nhà trọ - Nhanh chóng - Tiện lợi</p>
        </div>
    </div>

    <div id="login-right">
        <div class="login-card">
            <div class="login-title">Đăng Nhập</div>
            <p class="login-subtitle">Chào mừng bạn quay trở lại!</p>

            <form id="login-form">
                <div class="input-group-custom">
                    <input type="text" id="username" name="username" class="form-control-custom" placeholder="Tên đăng nhập" required>
                    <i class="fa-solid fa-user custom-icon"></i>
                </div>

                <div class="input-group-custom">
                    <input type="password" id="password" name="password" class="form-control-custom" placeholder="Mật khẩu" required>
                    <i class="fa-solid fa-lock custom-icon"></i>
                </div>

                <button type="submit" class="btn-login">
                    Đăng Nhập <i class="fa-solid fa-arrow-right"></i>
                </button>
                <div class="text-center mt-3">
                    <a href="customer/index.php" class="text-secondary small"><i class="fa fa-arrow-left"></i> Quay lại Trang chủ dành cho Khách</a>
                </div>
            </form>
        </div>
    </div>
</main>

</body>

<script>
    $('#login-form').submit(function(e){
        e.preventDefault()
        var btn = $(this).find('button[type="submit"]');
        var originalContent = btn.html();

        btn.attr('disabled',true).html('<i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...');

        if($(this).find('.alert-danger').length > 0 )
            $(this).find('.alert-danger').remove();

        $.ajax({
            url:'ajax.php?action=login',
            method:'POST',
            data:$(this).serialize(),
            error:err=>{
                console.log(err)
                btn.removeAttr('disabled').html(originalContent);
            },
            success:function(resp){
                if(resp == 1){
                    location.href ='index.php?page=home';
                }else{
                    $('#login-form').prepend('<div class="alert alert-danger text-center" style="border-radius:10px; margin-bottom:15px;">Tài khoản hoặc mật khẩu sai!</div>')
                    btn.removeAttr('disabled').html(originalContent);
                }
            }
        })
    })
</script>
</html>