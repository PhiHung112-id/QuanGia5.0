<!DOCTYPE html>
<html lang="en">

<?php session_start(); ?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : 'Admin Dashboard' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <?php
    if(!isset($_SESSION['login_id']))
        header('location:login.php');
    include('./header.php');
    // include('./auth.php');
    ?>

</head>
<style>
    /* --- CẤU HÌNH CHUNG --- */
    body {
        background: #f4f6f9; /* Màu nền sáng nhẹ, chuyên nghiệp hơn màu xám cũ */
        font-family: 'Poppins', sans-serif; /* Font chữ hiện đại */
        color: #333;
    }

    /* Hiệu ứng cuộn mượt mà */
    html {
        scroll-behavior: smooth;
    }

    /* --- PHẦN MAIN VIEW --- */
    main#view-panel {
        padding: 20px;
        margin-top: 60px; /* Tránh bị che bởi Navbar nếu có */
        min-height: calc(100vh - 60px);
        transition: all 0.3s;
    }

    /* --- MODAL (CỬA SỔ BẬT LÊN) --- */
    .modal-content {
        border-radius: 10px; /* Bo tròn góc */
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2); /* Đổ bóng tạo chiều sâu */
    }

    .modal-header {
        background: #007bff; /* Màu chủ đạo (có thể đổi theo ý thích) */
        color: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-dialog.large {
        width: 80% !important;
        max-width: unset;
    }
    .modal-dialog.mid-large {
        width: 50% !important;
        max-width: unset;
    }

    /* --- VIEWER MODAL (XEM ẢNH/VIDEO) --- */
    #viewer_modal .btn-close {
        position: absolute;
        z-index: 999999;
        right: 15px; /* Căn chỉnh lại cho đẹp */
        top: 15px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: unset;
        font-size: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    #viewer_modal .btn-close:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    #viewer_modal .modal-dialog {
        width: 90%;
        max-width: unset;
        height: 90%;
        margin: auto;
    }
    #viewer_modal .modal-content {
        background: rgba(0, 0, 0, 0.9); /* Nền tối trong suốt nhẹ */
        border: unset;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #viewer_modal img, #viewer_modal video {
        max-height: 90%;
        max-width: 90%;
        box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }

    /* --- PRELOADER (MÀN HÌNH CHỜ) --- */
    #preloader, #preloader2 {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        overflow: hidden;
        background: #fff;
    }
    #preloader:before, #preloader2:before {
        content: "";
        position: fixed;
        top: calc(50% - 30px);
        left: calc(50% - 30px);
        border: 6px solid #f2f2f2;
        border-top: 6px solid #007bff; /* Màu xoay loading */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        -webkit-animation: animate-preloader 1s linear infinite;
        animation: animate-preloader 1s linear infinite;
    }
    @keyframes animate-preloader {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* --- NÚT BACK TO TOP --- */
    .back-to-top {
        position: fixed;
        display: none;
        right: 15px;
        bottom: 15px;
        z-index: 99999;
    }
    .back-to-top i {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        width: 40px;
        height: 40px;
        border-radius: 50%; /* Bo tròn nút */
        background: #007bff;
        color: #fff;
        transition: all 0.4s;
    }
    .back-to-top i:hover {
        background: #0056b3;
        color: #fff;
    }
</style>

<body>
<?php include 'topbar.php' ?>
<?php include 'navbar.php' ?>

<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
    <div class="toast-body text-white">
    </div>
</div>

<main id="view-panel">
    <?php $page = isset($_GET['page']) ? $_GET['page'] :'home'; ?>
    <div class="container-fluid">
        <?php include $page.'.php' ?>
    </div>
</main>

<div id="preloader"></div>
<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='confirm' onclick="">Tiếp tục</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Lưu lại</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            <img src="" alt="">
        </div>
    </div>
</div>

</body>

<script>
    // Sửa lỗi thẻ <di> thành <div>
    window.start_load = function(){
        $('body').prepend('<div id="preloader2"></div>')
    }
    window.end_load = function(){
        $('#preloader2').fadeOut('fast', function() {
            $(this).remove();
        })
    }

    window.viewer_modal = function($src = ''){
        start_load()
        var t = $src.split('.')
        t = t[t.length - 1] // Lấy phần mở rộng file an toàn hơn
        if(t =='mp4'){
            var view = $("<video src='"+$src+"' controls autoplay></video>")
        }else{
            var view = $("<img src='"+$src+"' />")
        }
        $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
        $('#viewer_modal .modal-content').append(view)
        $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
        })
        end_load()
    }

    // Hàm này nằm ở file index.php hoặc admin_class.php (phần script cuối)
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_load()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size)
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                    }
                    $('#uni_modal').modal({
                        show:true,
                        backdrop:'static',
                        keyboard:false,
                        focus:true
                    })
                    end_load()
                }
            }
        })
    }

    window._conf = function($msg='',$func='',$params = []){
        $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
        $('#confirm_modal .modal-body').html($msg)
        $('#confirm_modal').modal('show')
    }

    window.alert_toast= function($msg = 'TEST',$bg = 'success'){
        $('#alert_toast').removeClass('bg-success')
        $('#alert_toast').removeClass('bg-danger')
        $('#alert_toast').removeClass('bg-info')
        $('#alert_toast').removeClass('bg-warning')

        if($bg == 'success')
            $('#alert_toast').addClass('bg-success')
        if($bg == 'danger')
            $('#alert_toast').addClass('bg-danger')
        if($bg == 'info')
            $('#alert_toast').addClass('bg-info')
        if($bg == 'warning')
            $('#alert_toast').addClass('bg-warning')

        // Thêm icon cho đẹp
        var icon = '';
        if($bg == 'success') icon = '<i class="fa fa-check-circle"></i> ';
        if($bg == 'danger') icon = '<i class="fa fa-exclamation-triangle"></i> ';

        $('#alert_toast .toast-body').html(icon + $msg)
        $('#alert_toast').toast({delay:3000}).toast('show');
    }

    $(document).ready(function(){
        $('#preloader').fadeOut('fast', function() {
            $(this).remove();
        })
    })

    // Khởi tạo datetimepicker nếu thư viện đã load
    if ($.fn.datetimepicker) {
        $('.datetimepicker').datetimepicker({
            format:'Y/m/d H:i',
            startDate: '+3d'
        })
    }

    // Khởi tạo select2 nếu thư viện đã load
    if ($.fn.select2) {
        $('.select2').select2({
            placeholder:"Vui lòng chọn",
            width: "100%"
        })
    }
</script>
</html>