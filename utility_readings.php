<?php include('db_connect.php');?>

<style>
    /* 1. Font & Layout chung */
    .container-fluid { font-family: 'Poppins', sans-serif; }

    /* 2. Card Container */
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }

    /* 3. Header */
    .card-header-custom {
        background: #fff;
        padding: 20px 30px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #4e73df;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* 4. Nút Thêm mới Gradient */
    .btn-gradient {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); /* Màu Vàng Cam cho Điện/Nước */
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(246, 194, 62, 0.4);
        transition: 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(246, 194, 62, 0.6);
        color: white;
    }

    /* 5. Table Style */
    .table-custom thead th {
        background-color: #f8f9fc;
        color: #555;
        font-weight: 700;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 15px;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
        font-size: 0.95rem;
        color: #444;
    }
    .table-custom tbody tr:hover { background-color: #fdfdfd; }

    /* 6. Các thành phần nhỏ (Badge, Icon) */
    .room-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .date-badge {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: 600;
        color: #6c757d;
    }

    .val-electric { color: #f6c23e; font-weight: 700; font-size: 1rem; }
    .val-water { color: #36b9cc; font-weight: 700; font-size: 1rem; }

    /* 7. Nút Hành động (Sửa/Xóa) */
    .btn-action {
        width: 35px; height: 35px;
        border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        border: none; transition: all 0.2s; margin: 0 3px;
    }
    .btn-edit { background: #e0fcfc; color: #36b9cc; }
    .btn-edit:hover { background: #36b9cc; color: white; }

    .btn-delete { background: #fceceb; color: #e74a3b; }
    .btn-delete:hover { background: #e74a3b; color: white; }

</style>

<div class="container-fluid p-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h4 class="card-title">
                        <span style="background: #fff3cd; padding: 8px; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; margin-right: 10px;">
                            <i class="fa fa-tachometer-alt text-warning"></i>
                        </span>
                        Ghi chỉ số Điện / Nước
                    </h4>
                    <button class="btn btn-gradient btn-sm" id="new_reading">
                        <i class="fa fa-plus-circle mr-2"></i> Ghi chỉ số mới
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Thời gian</th>
                                <th>Phòng</th>
                                <th>Khách thuê</th>
                                <th class="text-right">Chỉ số Điện</th>
                                <th class="text-right">Chỉ số Nước</th>
                                <th class="text-center" width="100">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $qry = $conn->query("SELECT u.*, h.house_no, t.lastname, t.firstname 
                                                    FROM utility_readings u 
                                                    INNER JOIN houses h ON h.id = u.house_id 
                                                    LEFT JOIN tenants t ON t.house_id = h.id AND t.status = 1 
                                                    ORDER BY date(u.reading_date) DESC");
                            while($row=$qry->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $i++ ?></td>

                                    <td>
                                        <span class="date-badge">
                                            <i class="fa fa-calendar-alt mr-1"></i>
                                            <?php echo date('m/Y', strtotime($row['reading_date'])) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="room-badge">P.<?php echo $row['house_no'] ?></span>
                                    </td>

                                    <td>
                                        <?php if(isset($row['lastname'])): ?>
                                            <div style="font-weight: 600; color: #333;">
                                                <i class="fa fa-user-circle text-secondary mr-1"></i>
                                                <?php echo $row['lastname'].' '.$row['firstname'] ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge badge-secondary font-weight-normal">Phòng trống</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-right">
                                        <span class="val-electric">
                                            <i class="fa fa-bolt mr-1"></i><?php echo number_format($row['electric']) ?>
                                        </span>
                                        <small class="text-muted d-block">kWh</small>
                                    </td>

                                    <td class="text-right">
                                        <span class="val-water">
                                            <i class="fa fa-tint mr-1"></i><?php echo number_format($row['water']) ?>
                                        </span>
                                        <small class="text-muted d-block">m³</small>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn-action btn-edit edit_reading" data-id="<?php echo $row['id'] ?>" title="Sửa">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn-action btn-delete delete_reading" data-id="<?php echo $row['id'] ?>" title="Xóa">
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
    // Thêm DataTable để phân trang, tìm kiếm
    $('table').dataTable({
        "language": {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "info": "Hiện _START_ đến _END_ của _TOTAL_ bản ghi",
            "paginate": { "next": '>', "previous": '<' }
        }
    })

    $('#new_reading').click(function(){
        uni_modal("Ghi chỉ số điện nước mới","manage_utility.php")
    })
    $('.edit_reading').click(function(){
        uni_modal("Sửa chỉ số","manage_utility.php?id="+$(this).attr('data-id'))
    })
    $('.delete_reading').click(function(){
        _conf("Bạn có chắc muốn xóa bản ghi này?","delete_reading",[$(this).attr('data-id')])
    })
    function delete_reading($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_utility',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Xóa thành công",'success')
                    setTimeout(function(){ location.reload() },1500)
                }
            }
        })
    }
</script>