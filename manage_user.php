<?php
include('db_connect.php');
// Bỏ session_start() nếu file này được load qua Ajax từ trang index (đã có session) để tránh lỗi warning
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>

<style>
    /* --- STYLE CHO FORM --- */
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    .control-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    /* Input Group đẹp hơn */
    .input-group-text {
        background: #f8f9fa;
        border-color: #ddd;
        color: #007bff;
        border-radius: 8px 0 0 8px;
    }

    .form-control, .custom-select {
        border-radius: 0 8px 8px 0; /* Bo tròn bên phải */
        border: 1px solid #ddd;
        padding: 10px 15px;
        height: auto;
        font-size: 0.95rem;
    }

    .form-control:focus, .custom-select:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    /* Riêng select không nằm trong input-group thì bo tròn cả 2 bên */
    select.custom-select:not(.in-group) {
        border-radius: 8px;
    }
</style>

<div class="container-fluid">
    <div id="msg"></div>

    <form action="" id="manage-user">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">

        <div class="form-group">
            <label for="name" class="control-label">Họ và Tên</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required placeholder="Nhập họ tên...">
            </div>
        </div>

        <div class="form-group">
            <label for="username" class="control-label">Tên đăng nhập</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-at"></i></span>
                </div>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required autocomplete="off" placeholder="Nhập tên đăng nhập...">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="control-label">Mật khẩu</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                </div>
                <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" placeholder="Nhập mật khẩu...">
            </div>
            <?php if(isset($meta['id'])): ?>
                <small class="text-muted"><i>* Để trống nếu bạn không muốn thay đổi mật khẩu cũ.</i></small>
            <?php endif; ?>
        </div>

        <?php if(isset($meta['type']) && $meta['type'] == 3): ?>
            <input type="hidden" name="type" value="3">
        <?php else: ?>
            <?php if(!isset($_GET['mtype'])): ?>
                <div class="form-group">
                    <label for="type" class="control-label">Loại tài khoản</label>
                    <select name="type" id="type" class="custom-select in-group" style="border-radius: 8px;">
                        <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Nhân viên (Staff)</option>
                        <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Quản trị viên (Admin)</option>
                    </select>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </form>
</div>

<script>
    $('#manage-user').submit(function(e){
        e.preventDefault();
        start_load()
        $('#msg').html('') // Xóa thông báo cũ trước khi gửi

        $.ajax({
            url:'ajax.php?action=save_user',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    alert_toast("Lưu dữ liệu thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                } else if(resp == 2){
                    // Chỉ hiện lỗi này khi server trả về đúng mã số 2
                    $('#msg').html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i> Tên đăng nhập này đã tồn tại.</div>')
                    end_load()
                } else {
                    // Trường hợp lỗi khác (ví dụ code PHP bị lỗi cú pháp)
                    $('#msg').html('<div class="alert alert-danger text-center">Đã xảy ra lỗi hệ thống. Vui lòng thử lại.</div>')
                    console.log(resp); // Xem lỗi trong console
                    end_load()
                }
            }
        })
    })
</script>