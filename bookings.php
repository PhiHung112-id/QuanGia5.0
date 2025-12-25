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

    /* Badge trạng thái */
    .badge-status { padding: 8px 12px; border-radius: 30px; font-weight: 500; font-size: 0.85rem; }
    .status-pending { background: #fff3cd; color: #856404; } /* Chờ duyệt */
    .status-confirmed { background: #d4edda; color: #155724; } /* Đã duyệt */
    .status-cancelled { background: #f8d7da; color: #721c24; } /* Đã hủy */

    .table-custom tbody td { vertical-align: middle; }

    /* Nút hành động */
    .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.2s; margin: 0 3px; cursor: pointer; }
    .btn-check { background: #e8f5e9; color: #2e7d32; } /* Nút Duyệt */
    .btn-check:hover { background: #2e7d32; color: white; }

    .btn-cancel { background: #fff3e0; color: #ef6c00; } /* Nút Hủy */
    .btn-cancel:hover { background: #ef6c00; color: white; }

    .btn-delete { background: #ffebee; color: #c62828; } /* Nút Xóa */
    .btn-delete:hover { background: #c62828; color: white; }
</style>

<div class="container-fluid p-4">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h4 class="m-0 font-weight-bold text-dark">
                    <i class="fa fa-calendar-check text-primary mr-2"></i> Danh sách Đặt phòng
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-custom table-hover" id="booking_tbl">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th>Khách hàng</th>
                            <th>Thông tin Phòng</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        // Join 3 bảng: bookings, customers, houses
                        $qry = $conn->query("SELECT b.*, c.name as cname, c.phone, c.email, h.house_no, h.price, cat.name as cat_name 
                                                FROM bookings b 
                                                INNER JOIN customers c ON c.id = b.customer_id 
                                                INNER JOIN houses h ON h.id = b.house_id 
                                                INNER JOIN categories cat ON cat.id = h.category_id
                                                ORDER BY b.id DESC");
                        while($row = $qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center text-muted"><?php echo $i++ ?></td>

                                <td>
                                    <p class="font-weight-bold mb-1 text-dark"><?php echo ucwords($row['cname']) ?></p>
                                    <p class="small text-muted mb-0"><i class="fa fa-phone fa-xs"></i> <?php echo $row['phone'] ?></p>
                                    <p class="small text-muted mb-0"><i class="fa fa-envelope fa-xs"></i> <?php echo $row['email'] ?></p>
                                </td>

                                <td>
                                    <p class="font-weight-bold mb-1 text-primary">Phòng: <?php echo $row['house_no'] ?></p>
                                    <p class="small text-muted mb-0">Loại: <?php echo $row['cat_name'] ?></p>
                                    <span class="badge badge-light border mt-1">
                                        <?php echo number_format($row['price'], 0, ',', '.') ?> VNĐ
                                    </span>
                                </td>

                                <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge-status status-pending">Chờ duyệt</span>
                                    <?php elseif($row['status'] == 1): ?>
                                        <span class="badge-status status-confirmed">Đã duyệt</span>
                                    <?php else: ?>
                                        <span class="badge-status status-cancelled">Đã hủy</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                        <button class="btn-action btn-check confirm_booking" type="button" data-id="<?php echo $row['id'] ?>" title="Duyệt đơn này">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn-action btn-cancel cancel_booking" type="button" data-id="<?php echo $row['id'] ?>" title="Hủy đơn này">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php endif; ?>

                                    <button class="btn-action btn-delete delete_booking" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa đơn này">
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
    // Khởi tạo Datatable với CẤU HÌNH TIẾNG VIỆT FULL
    $('table').dataTable({
        "language": {
            "sProcessing":   "Đang xử lý...",
            "sLengthMenu":   "Xem _MENU_ mục",
            "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
            "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
            "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix":  "",
            "sSearch":       "Tìm kiếm:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Đầu",
                "sPrevious": "Trước",
                "sNext":     "Tiếp",
                "sLast":     "Cuối"
            },
            "sEmptyTable": "Không có dữ liệu đặt phòng nào"
        }
    });

    // 1. Xử lý Duyệt đơn
    $(document).on('click', '.confirm_booking', function(){
        _conf("Bạn có chắc chắn muốn DUYỆT yêu cầu đặt phòng này?", "update_booking_status", [$(this).attr('data-id'), 1])
    })

    // 2. Xử lý Hủy đơn
    $(document).on('click', '.cancel_booking', function(){
        _conf("Bạn có chắc chắn muốn HỦY yêu cầu này?", "update_booking_status", [$(this).attr('data-id'), 2])
    })

    // 3. Xử lý Xóa đơn
    $(document).on('click', '.delete_booking', function(){
        _conf("Bạn có chắc chắn muốn XÓA dữ liệu này vĩnh viễn?", "delete_booking", [$(this).attr('data-id')])
    })

    function update_booking_status($id, $status){
        start_load()
        $.ajax({
            url: 'ajax.php?action=update_booking_status',
            method: 'POST',
            data: {id: $id, status: $status},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Cập nhật trạng thái thành công", 'success')
                    setTimeout(function(){
                        location.reload()
                    }, 1500)
                }
            }
        })
    }

    function delete_booking($id){
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_booking',
            method: 'POST',
            data: {id: $id},
            success: function(resp){
                if(resp == 1){
                    alert_toast("Đã xóa dữ liệu thành công", 'success')
                    setTimeout(function(){
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>