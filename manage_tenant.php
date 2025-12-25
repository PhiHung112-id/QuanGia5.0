<?php
include 'db_connect.php';
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
}
?>

<style>
    /* --- STYLE CHO FORM MODAL --- */
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Tiêu đề từng phần */
    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #007bff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .control-label {
        font-weight: 600;
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    /* Input có icon */
    .input-group-text {
        background: #f8f9fa;
        border-color: #ced4da;
        color: #6c757d;
        border-radius: 8px 0 0 8px;
        font-size: 0.9rem;
    }

    .form-control, .custom-select {
        border-radius: 0 8px 8px 0 !important; /* Bo tròn bên phải */
        font-size: 0.95rem;
        height: auto;
        padding: 10px;
    }

    /* Riêng select2 thì cần chỉnh lại chút border */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border-radius: 0 8px 8px 0 !important;
        border: 1px solid #ced4da;
        padding: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }

    /* Hiệu ứng focus */
    .form-control:focus {
        box-shadow: none;
        border-color: #007bff;
    }
</style>

<div class="container-fluid">
    <form action="" id="manage-tenant">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

        <div class="section-title mt-2">
            <i class="fa fa-user-circle"></i> Thông tin cá nhân
        </div>

        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Họ</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="lastname" value="<?php echo isset($lastname) ? $lastname :'' ?>" required placeholder="Nguyễn">
                </div>
            </div>

            <div class="col-md-4">
                <label class="control-label">Tên đệm</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
                    </div>
                    <input type="text" class="form-control" name="middlename" value="<?php echo isset($middlename) ? $middlename :'' ?>" placeholder="Văn">
                </div>
            </div>

            <div class="col-md-4">
                <label class="control-label">Tên</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-signature"></i></span>
                    </div>
                    <input type="text" class="form-control" name="firstname" value="<?php echo isset($firstname) ? $firstname :'' ?>" required placeholder="A">
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-6">
                <label class="control-label">Email liên hệ</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>" required placeholder="email@example.com">
                </div>
            </div>

            <div class="col-md-6">
                <label class="control-label">Số điện thoại</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    </div>
                    <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact :'' ?>" required placeholder="09xxxxxxx">
                </div>
            </div>
        </div>

        <div class="section-title mt-4">
            <i class="fa fa-home"></i> Thông tin thuê phòng
        </div>

        <div class="row form-group">
            <div class="col-md-6">
                <label class="control-label">Chọn Phòng / Nhà</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-door-open"></i></span>
                    </div>
                    <select name="house_id" id="" class="custom-select select2">
                        <option value=""></option>
                        <?php
                        // Query lấy danh sách nhà chưa có người thuê (hoặc chính nhà của khách này nếu đang sửa)
                        $house = $conn->query("SELECT * FROM houses where id not in (SELECT house_id from tenants where status = 1) ".(isset($house_id)? " or id = $house_id": "" )." ");
                        while($row= $house->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="control-label">Ngày bắt đầu thuê</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" class="form-control" name="date_in" value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#manage-tenant').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_tenant',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Lưu dữ liệu khách thuê thành công.",'success')
                    setTimeout(function(){
                        location.reload()
                    },1000)
                }
            }
        })
    })
</script>