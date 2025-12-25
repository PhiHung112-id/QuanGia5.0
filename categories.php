<?php include('db_connect.php');?>

<style>
    /* --- STYLE GIAO DIỆN --- */
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Style chung cho Card */
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
        height: 100%; /* Để 2 cột cao bằng nhau nếu cần */
    }

    .card-header-custom {
        background: #fff;
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 700;
        color: #333;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Style cho Form */
    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 10px 15px;
        height: auto;
        transition: 0.3s;
    }

    .form-control-custom:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    /* Nút bấm */
    .btn-gradient {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        transition: all 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #f1f3f5;
        color: #666;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn-cancel:hover {
        background: #e9ecef;
        color: #333;
    }

    /* Style cho Bảng */
    .table-custom thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 15px;
    }

    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
        color: #444;
    }

    .table-custom tbody tr:hover {
        background-color: #fcfcfc;
    }

    /* Nút hành động nhỏ */
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        margin: 0 3px;
    }
    .btn-edit { background: #e3f2fd; color: #1976d2; }
    .btn-edit:hover { background: #1976d2; color: white; }
    .btn-delete { background: #ffebee; color: #c62828; }
    .btn-delete:hover { background: #c62828; color: white; }

</style>

<div class="container-fluid p-4">

    <div class="row">
        <div class="col-md-4 mb-4">
            <form action="" id="manage-category">
                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="fa fa-pen-square text-primary"></i> Thông tin Danh mục
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label class="control-label">Tên Danh mục / Loại phòng</label>
                            <input type="text" class="form-control form-control-custom" name="name" placeholder="VD: Phòng VIP, Căn hộ..." required>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0 pb-4 text-center">
                        <button class="btn btn-sm btn-gradient mr-2"> Lưu lại</button>
                        <button class="btn btn-sm btn-cancel" type="button" onclick="cancel_edit()"> Hủy bỏ</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <i class="fa fa-list text-primary"></i> Danh sách Danh mục
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Tên Danh mục</th>
                                <th class="text-center" width="150">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $category = $conn->query("SELECT * FROM categories order by id asc");
                            while($row=$category->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $i++ ?></td>
                                    <td>
                                        <b style="color: #333; font-size: 1rem;"><?php echo $row['name'] ?></b>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-action btn-edit edit_category" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" title="Sửa">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn-action btn-delete delete_category" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm reset form khi bấm hủy
    function cancel_edit(){
        $('#manage-category').get(0).reset();
        $('#manage-category').find("[name='id']").val('');
    }

    $('#manage-category').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_category',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Thêm danh mục thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
                else if(resp==2){
                    alert_toast("Cập nhật thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    })

    $('.edit_category').click(function(){
        // Cuộn lên đầu trang (nếu danh sách dài)
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        var cat = $('#manage-category')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='name']").val($(this).attr('data-name'))

        // Focus vào ô nhập liệu để sửa luôn
        cat.find("[name='name']").focus();
    })

    $('.delete_category').click(function(){
        _conf("Bạn có chắc chắn muốn xóa danh mục này?","delete_category",[$(this).attr('data-id')])
    })

    function delete_category($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_category',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Đã xóa danh mục",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }

    // Cấu hình bảng
    $('table').dataTable({
        "language": {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ mục",
            "info": "Hiện _START_ đến _END_ của _TOTAL_ mục",
            "paginate": {
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            }
        }
    })
</script>