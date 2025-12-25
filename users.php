<?php include 'db_connect.php'; ?>

<style>
    /* --- STYLE GIAO DIỆN --- */

    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Card bao quanh bảng */
    .card-users {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Bóng đổ mềm hơn */
        background: #fff;
        overflow: hidden;
    }

    /* Header của Card */
    .card-header-custom {
        background: #fff;
        padding: 25px 30px; /* Tăng padding để thoáng hơn */
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    /* Nút Thêm Mới */
    .btn-add-new {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 12px 25px; /* Nút to hơn một chút */
        border-radius: 50px;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        transition: all 0.3s;
    }

    .btn-add-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        color: white;
    }

    /* Phần Body chứa bảng */
    .card-body-custom {
        padding: 20px 30px; /* KHOẢNG CÁCH NỘI DUNG Ở ĐÂY */
    }

    /* Tùy chỉnh Table */
    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px; /* Tạo khoảng cách giữa các dòng (nếu muốn tách rời) */
        margin-top: -10px; /* Cân chỉnh lại do border-spacing */
    }

    .table-custom thead th {
        background-color: transparent;
        color: #666;
        font-weight: 600;
        border-bottom: 2px solid #eee;
        padding: 15px 10px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-custom tbody tr {
        background: #fff;
        transition: all 0.2s;
    }

    .table-custom tbody tr:hover {
        background-color: #f9fbfd; /* Màu nền nhẹ khi rê chuột */
        transform: scale(1.005); /* Phóng to cực nhẹ tạo cảm giác nổi */
    }

    .table-custom tbody td {
        padding: 20px 15px; /* Tăng padding dòng để thoáng (quan trọng) */
        vertical-align: middle;
        border-bottom: 1px solid #f2f2f2;
        color: #444;
        font-size: 0.95rem;
    }

    /* Badge vai trò */
    .badge-role {
        padding: 8px 15px;
        border-radius: 8px; /* Bo góc vuông nhẹ */
        font-size: 0.8rem;
        font-weight: 600;
    }
    .badge-admin { background: #e3f2fd; color: #1565c0; }
    .badge-staff { background: #e8f5e9; color: #2e7d32; }
    .badge-user { background: #fff3e0; color: #ef6c00; }

    /* Nút hành động */
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px; /* Bo góc vuông nhẹ thay vì tròn */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        margin: 0 5px;
    }

    .btn-edit { background: #f0f7ff; color: #007bff; }
    .btn-edit:hover { background: #007bff; color: white; }

    .btn-delete { background: #fff0f0; color: #dc3545; }
    .btn-delete:hover { background: #dc3545; color: white; }

</style>

<div class="container-fluid p-4">

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-users">

                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        <div style="width: 5px; height: 25px; background: #007bff; margin-right: 15px; border-radius: 5px;"></div>
                        <h4 class="card-title">Danh sách Người dùng</h4>
                    </div>
                    <button class="btn btn-add-new btn-sm" id="new_user">
                        <i class="fa fa-plus mr-2"></i> Thêm mới
                    </button>
                </div>

                <div class="card-body card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-custom" id="user_tbl">
                            <thead>
                            <tr>
                                <th class="text-center" width="60">#</th>
                                <th>Họ và tên</th>
                                <th>Tên đăng nhập</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center" width="160">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $type = array("","Admin","Nhân viên","Người dùng");
                            $users = $conn->query("SELECT * FROM users order by name asc");
                            $i = 1;
                            while($row= $users->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center text-muted font-weight-bold"><?php echo $i++ ?></td>
                                    <td>
                                        <b style="color: #2c3e50; font-size: 1rem;"><?php echo ucwords($row['name']) ?></b>
                                    </td>
                                    <td>
                                        <span class="text-secondary"><?php echo $row['username'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $role_badge = '';
                                        if($row['type'] == 1) $role_badge = 'badge-role badge-admin';
                                        elseif($row['type'] == 2) $role_badge = 'badge-role badge-staff';
                                        else $role_badge = 'badge-role badge-user';
                                        ?>
                                        <span class="<?php echo $role_badge ?>">
                                            <?php echo isset($type[$row['type']]) ? $type[$row['type']] : "Khác" ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-action btn-edit edit_user" type="button" data-id="<?php echo $row['id'] ?>" title="Sửa">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn-action btn-delete delete_user" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div> </div>
        </div>
    </div>
</div>

<script>
    // Cấu hình DataTables thoáng hơn
    $('table').dataTable({
        "lengthMenu": [ 5, 10, 25, 50 ],
        "pageLength": 5, // Mặc định hiện 5 dòng cho đỡ dài
        "language": {
            "search": "Tìm kiếm nhanh:",
            "lengthMenu": "Hiển thị _MENU_",
            "info": "Tổng số: _TOTAL_ tài khoản",
            "paginate": {
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            }
        }
    });

    $('#new_user').click(function(){
        uni_modal('Thêm Người Dùng Mới','manage_user.php')
    })

    $('.edit_user').click(function(){
        uni_modal('Cập nhật thông tin','manage_user.php?id='+$(this).attr('data-id'))
    })

    $('.delete_user').click(function(){
        _conf("Bạn có chắc chắn muốn xóa người dùng này?","delete_user",[$(this).attr('data-id')])
    })

    function delete_user($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_user',
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