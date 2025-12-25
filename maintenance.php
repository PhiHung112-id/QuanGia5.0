<?php include('db_connect.php');?>

<style>
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
        border-radius: 15px 15px 0 0;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        transition: 0.3s;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
    }

    /* Badge trạng thái */
    .badge-status { padding: 6px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
    .status-0 { background: #ffebee; color: #c62828; } /* Chờ xử lý */
    .status-1 { background: #e3f2fd; color: #1565c0; } /* Đang sửa */
    .status-2 { background: #e8f5e9; color: #2e7d32; } /* Đã xong */

    .table-custom thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 15px;
    }

    .table-custom tbody td { padding: 15px; vertical-align: middle; border-top: 1px solid #f0f0f0; }
    .table-custom tbody tr:hover { background-color: #fcfcfc; }

    /* Nút hành động */
    .btn-action {
        width: 35px; height: 35px;
        border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        border: none; margin: 0 2px;
        transition: 0.2s;
    }
    .btn-edit { background: #e3f2fd; color: #1976d2; }
    .btn-edit:hover { background: #1976d2; color: white; }
    .btn-delete { background: #ffebee; color: #c62828; }
    .btn-delete:hover { background: #c62828; color: white; }

</style>

<div class="container-fluid p-4">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h4 class="m-0 font-weight-bold text-dark"><i class="fa fa-tools text-danger mr-2"></i> Danh sách Sự cố & Sửa chữa</h4>
                <button class="btn btn-gradient btn-sm" id="new_complaint">
                    <i class="fa fa-plus-circle"></i> Báo cáo sự cố
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom" id="complaint_tbl">
                        <thead>
                        <tr>
                            <th class="text-center" width="50">#</th>
                            <th>Ngày báo</th>
                            <th>Người báo / Phòng</th>
                            <th>Nội dung sự cố</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-right">Chi phí</th>
                            <th class="text-center" width="100">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        // Join bảng complaints, tenants, houses để lấy tên và số phòng
                        $complaints = $conn->query("SELECT c.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as tname, h.house_no FROM complaints c INNER JOIN tenants t ON t.id = c.tenant_id INNER JOIN houses h ON h.id = t.house_id ORDER BY c.status ASC, c.date_created DESC");
                        while($row=$complaints->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center text-muted"><?php echo $i++ ?></td>
                                <td><?php echo date('d/m/Y',strtotime($row['date_created'])) ?></td>
                                <td>
                                    <b style="color: #333;"><?php echo ucwords($row['tname']) ?></b><br>
                                    <small class="text-muted"><i class="fa fa-door-open"></i> Phòng: <b><?php echo $row['house_no'] ?></b></small>
                                </td>
                                <td><?php echo $row['report'] ?></td>
                                <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge badge-status status-0">Chờ xử lý</span>
                                    <?php elseif($row['status'] == 1): ?>
                                        <span class="badge badge-status status-1">Đang sửa</span>
                                    <?php else: ?>
                                        <span class="badge badge-status status-2">Hoàn thành</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    <?php echo $row['cost'] > 0 ? number_format($row['cost'],0,',','.').' đ' : '-' ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action btn-edit edit_complaint" data-id="<?php echo $row['id'] ?>" title="Cập nhật"><i class="fa fa-pen"></i></button>
                                    <button class="btn-action btn-delete delete_complaint" data-id="<?php echo $row['id'] ?>" title="Xóa"><i class="fa fa-trash"></i></button>
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
    // Cấu hình DataTables Tiếng Việt hoàn toàn
    $('table').dataTable({
        "language": {
            "decimal":        "",
            "emptyTable":     "Không có dữ liệu sự cố nào",
            "info":           "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
            "infoEmpty":      "Hiển thị 0 đến 0 của 0 mục",
            "infoFiltered":   "(lọc từ _MAX_ tổng số mục)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Hiển thị _MENU_ dòng",
            "loadingRecords": "Đang tải...",
            "processing":     "Đang xử lý...",
            "search":         "Tìm kiếm:",
            "zeroRecords":    "Không tìm thấy kết quả phù hợp",
            "paginate": {
                "first":      "Đầu",
                "last":       "Cuối",
                "next":       '<i class="fa fa-chevron-right"></i>',
                "previous":   '<i class="fa fa-chevron-left"></i>'
            },
            "aria": {
                "sortAscending":  ": sắp xếp tăng dần",
                "sortDescending": ": sắp xếp giảm dần"
            }
        }
    });

    $('#new_complaint').click(function(){
        uni_modal("Ghi nhận Sự cố Mới","manage_maintenance.php","mid-large")
    })
    $('.edit_complaint').click(function(){
        uni_modal("Cập nhật Tiến độ / Chi phí","manage_maintenance.php?id="+$(this).attr('data-id'),"mid-large")
    })
    $('.delete_complaint').click(function(){
        _conf("Bạn có chắc chắn muốn xóa phiếu này?","delete_complaint",[$(this).attr('data-id')])
    })
    function delete_complaint($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_complaint',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Đã xóa thành công",'success')
                    setTimeout(function(){ location.reload() },1500)
                }
            }
        })
    }
</script>