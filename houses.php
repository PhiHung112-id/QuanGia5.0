<?php include('db_connect.php');?>

<style>
    /* --- STYLE GIAO DIỆN --- */
    .container-fluid { font-family: 'Poppins', sans-serif; }

    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
    }

    .card-header-custom {
        background: #fff;
        padding: 20px 30px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        transition: 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
        color: white;
    }

    /* Table */
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
    .table-custom tbody tr:hover { background-color: #fcfcfc; }

    /* Nút hành động */
    .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.2s; margin: 0 3px; cursor: pointer; }
    .btn-edit { background: #e3f2fd; color: #1976d2; }
    .btn-edit:hover { background: #1976d2; color: white; }
    .btn-delete { background: #ffebee; color: #c62828; }
    .btn-delete:hover { background: #c62828; color: white; }

    /* Thông tin */
    .house-info p { margin-bottom: 5px; font-size: 0.9rem; display: flex; align-items: center; }
    .badge-price { background: #e8f5e9; color: #2e7d32; padding: 5px 10px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
    .badge-category { background: #fff3e0; color: #ef6c00; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; }
</style>

<div class="container-fluid p-4">
    <div class="col-lg-12">
        <div class="card card-custom">

            <div class="card-header-custom">
                <h4 class="m-0 font-weight-bold text-dark">
                    <i class="fa fa-home text-primary mr-2"></i> Danh sách Nhà / Phòng
                </h4>
                <button class="btn btn-gradient btn-sm" id="new_house">
                    <i class="fa fa-plus-circle"></i> Thêm phòng mới
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom table-hover" id="house_tbl">
                        <thead>
                        <tr>
                            <th class="text-center" width="50">#</th>
                            <th>Thông tin chi tiết</th>
                            <th class="text-center" width="100">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        $house = $conn->query("SELECT h.*,c.name as cname FROM houses h inner join categories c on c.id = h.category_id order by id asc");
                        while($row=$house->fetch_assoc()):
                            // Kiểm tra ảnh
                            $img_src = !empty($row['img_path']) && file_exists('assets/uploads/'.$row['img_path']) ? 'assets/uploads/'.$row['img_path'] : 'assets/uploads/no-image.jpg';
                            ?>
                            <tr>
                                <td class="text-center text-muted"><?php echo $i++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $img_src ?>" class="img-thumbnail mr-3" style="width: 100px; height: 75px; object-fit: cover; border-radius: 8px;">

                                        <div class="house-info">
                                            <p style="font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 5px;">
                                                <i class="fa fa-door-open text-primary mr-2"></i> <?php echo $row['house_no'] ?>
                                            </p>
                                            <p>
                                                <span class="badge-category"><?php echo $row['cname'] ?></span>
                                                <span class="badge-price ml-2"><?php echo number_format($row['price'], 0, ',', '.') ?> VNĐ</span>
                                            </p>
                                            <p class="text-muted small mt-1">
                                                <i class="fa fa-align-left mr-1"></i> <?php echo $row['description'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action btn-edit edit_house" type="button" data-id="<?php echo $row['id'] ?>" title="Sửa">
                                        <i class="fa fa-pen"></i>
                                    </button>
                                    <button class="btn-action btn-delete delete_house" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa">
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

<script>
    // Khởi tạo DataTables
    $('table').dataTable({
        "language": {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "info": "Hiện _START_ đến _END_ của _TOTAL_ phòng",
            "paginate": {
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            }
        }
    });

    // Sự kiện Thêm mới
    $('#new_house').click(function(){
        uni_modal("Thêm Phòng Mới","manage_house.php","mid-large")
    })

    // --- SỬA LỖI PAGINATION (TRANG 2) ---
    // Sử dụng $(document).on('click', ...) thay vì $('.class').click(...)

    // Sự kiện Sửa
    $(document).on('click', '.edit_house', function(){
        uni_modal("Cập nhật thông tin Phòng","manage_house.php?id="+$(this).attr('data-id'),"mid-large")
    })

    // Sự kiện Xóa
    $(document).on('click', '.delete_house', function(){
        _conf("Bạn có chắc chắn muốn xóa phòng này không?","delete_house",[$(this).attr('data-id')])
    })

    function delete_house($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_house',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Đã xóa thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }
</script>