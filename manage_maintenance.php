<?php
include 'db_connect.php';
// Kiểm tra nếu có ID thì lấy dữ liệu cũ lên để sửa
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM complaints where id= ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
}
?>
<style>
    .container-fluid { font-family: 'Poppins', sans-serif; }
    .control-label { font-weight: 600; color: #555; margin-bottom: 5px;}
    .form-control, .custom-select { border-radius: 5px; }
</style>

<div class="container-fluid">
    <form action="" id="manage-complaint">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

        <div class="form-group">
            <label class="control-label">Người báo cáo / Phòng</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2" required>
                <option value=""></option>
                <?php
                // Lấy danh sách khách thuê
                $tenant = $conn->query("SELECT t.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, h.house_no, h.id as hid FROM tenants t INNER JOIN houses h ON h.id = t.house_id WHERE t.status = 1 ORDER BY h.house_no ASC");
                while($row=$tenant->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" data-house-id="<?php echo $row['hid'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>>
                        Phòng <?php echo $row['house_no'] ?> - <?php echo ucwords($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="house_id" id="house_id" value="<?php echo isset($house_id) ? $house_id : '' ?>">
        </div>

        <div class="form-group">
            <label class="control-label">Nội dung sự cố (Report)</label>
            <textarea name="report" cols="30" rows="3" class="form-control" required placeholder="Mô tả chi tiết sự cố..."><?php echo isset($report) ? $report : '' ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Trạng thái</label>
                    <select name="status" class="custom-select">
                        <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Đang sửa chữa</option>
                        <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Đã hoàn thành</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Chi phí (VNĐ)</label>
                    <input type="number" step="any" name="cost" class="form-control text-right" min="0" value="<?php echo isset($cost) ? $cost : 0 ?>">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Khởi tạo Select2 cho đẹp
    $('.select2').select2({
        placeholder: "Chọn phòng / khách thuê",
        width: "100%"
    });

    // Tự động lấy House ID khi chọn Khách
    $('#tenant_id').change(function(){
        var hid = $(this).find(':selected').attr('data-house-id');
        $('#house_id').val(hid);
    });

    // Xử lý khi nhấn nút LƯU
    $('#manage-complaint').submit(function(e){
        e.preventDefault();
        start_load();

        $.ajax({
            url: 'ajax.php?action=save_complaint', // Đảm bảo action là SAVE
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if(resp == 1){
                    alert_toast("Dữ liệu đã được lưu thành công", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                } else {
                    // Nếu lỗi, hiện thông báo để biết đường sửa
                    alert_toast("Có lỗi xảy ra! Vui lòng kiểm tra lại.", 'danger');
                    end_load();
                    console.log(resp); // Bật F12 để xem lỗi cụ thể nếu có
                }
            }
        });
    });
</script>