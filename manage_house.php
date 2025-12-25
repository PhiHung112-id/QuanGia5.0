<?php
include('db_connect.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM houses where id = ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-house" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>

        <div class="form-group">
            <label class="control-label">Số nhà / Số phòng</label>
            <input type="text" class="form-control" name="house_no" required value="<?php echo isset($house_no) ? $house_no : '' ?>">
        </div>

        <div class="form-group">
            <label class="control-label">Vị trí / Địa chỉ cụ thể</label>
            <textarea name="location" class="form-control" rows="2" required placeholder="Ví dụ: Tầng 2, Tòa nhà A, 123 Đường ABC..."><?php echo isset($location) ? $location : '' ?></textarea>
        </div>

        <div class="form-group">
            <label class="control-label">Link Google Maps</label>
            <input type="text" class="form-control" name="map_link" placeholder="https://goo.gl/maps/..." value="<?php echo isset($map_link) ? $map_link : '' ?>">
            <small class="form-text text-muted">
                <i>Cách lấy: Vào Google Maps > Tìm địa điểm > Chia sẻ > Sao chép liên kết > Dán vào đây.</i>
            </small>
        </div>

        <div class="form-group">
            <label class="control-label">Loại phòng</label>
            <select name="category_id" class="custom-select" required>
                <option value="" disabled selected>-- Chọn loại --</option>
                <?php
                $categories = $conn->query("SELECT * FROM categories order by name asc");
                while($row= $categories->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Mô tả chi tiết</label>
            <textarea name="description" cols="30" rows="3" class="form-control" required><?php echo isset($description) ? $description : '' ?></textarea>
        </div>

        <div class="form-group">
            <label class="control-label">Giá thuê (VNĐ)</label>
            <input type="number" class="form-control text-right" name="price" min="0" step="any" required value="<?php echo isset($price) ? $price : '' ?>">
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6 border-right">
                <div class="form-group">
                    <label for="" class="control-label"><b>Ảnh đại diện (Ảnh bìa)</b></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
                        <label class="custom-file-label" for="customFile">Chọn 1 ảnh...</label>
                    </div>
                </div>
                <div class="form-group text-center">
                    <img src="<?php echo isset($img_path) && !empty($img_path) ? 'assets/uploads/'.$img_path : 'assets/uploads/no-image.jpg' ?>" alt="" id="cimg" class="img-fluid img-thumbnail" style="max-height: 200px; width: 100%; object-fit: cover;">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label"><b>Bộ sưu tập ảnh chi tiết</b></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFileMulti" name="images[]" multiple>
                        <label class="custom-file-label" for="customFileMulti">Chọn nhiều ảnh...</label>
                    </div>
                    <small class="form-text text-muted mt-2">
                        <i class="fa fa-info-circle"></i> Giữ phím <b>Ctrl</b> để chọn nhiều ảnh cùng lúc.
                    </small>
                </div>

                <?php if(isset($id)): ?>
                    <label class="control-label">Ảnh đã tải lên:</label>
                    <div class="row mt-2" style="max-height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px;">
                        <?php
                        // Lấy ảnh từ bảng house_images
                        $imgs = $conn->query("SELECT * FROM house_images WHERE house_id = $id");
                        if($imgs->num_rows > 0):
                            while($row_img = $imgs->fetch_assoc()):
                                if(!empty($row_img['img_path']) && file_exists('assets/uploads/'.$row_img['img_path'])):
                                    ?>
                                    <div class="col-4 mb-2 text-center">
                                        <img src="assets/uploads/<?php echo $row_img['img_path'] ?>" class="img-thumbnail" style="width:100%; height:70px; object-fit:cover;">
                                    </div>
                                <?php
                                endif;
                            endwhile;
                        else:
                            echo '<div class="col-12 text-muted small text-center">Chưa có ảnh chi tiết nào.</div>';
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </form>
</div>

<script>
    // Hàm hiển thị ảnh xem trước (cho ảnh đại diện)
    function displayImg(input,_this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Xử lý hiển thị tên file khi chọn (UX)
    $(".custom-file-input").on("change", function() {
        if($(this).attr('name') == 'images[]'){
            var files = $(this)[0].files;
            if(files.length > 1){
                $(this).siblings(".custom-file-label").addClass("selected").html(files.length + " ảnh đã chọn");
            } else if(files.length == 1) {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            }
        }
        else {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        }
    });

    // Xử lý Submit Form
    $('#manage-house').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('') // Xóa thông báo cũ

        $.ajax({
            url:'ajax.php?action=save_house',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Lưu dữ liệu thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1000)
                }
                else if(resp==2){
                    // Lỗi trùng số phòng
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Số phòng này đã tồn tại trong hệ thống.</div>')
                    end_load()
                }
                else if(resp==3){
                    // Lỗi tiền âm (MỚI THÊM)
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Giá thuê phòng không được nhỏ hơn 0.</div>')
                    end_load()
                    // Cuộn chuột lên đầu để người dùng thấy lỗi
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }
                else{
                    $('#msg').html('<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại.</div>')
                    end_load()
                }
            }
        })
    })
</script>