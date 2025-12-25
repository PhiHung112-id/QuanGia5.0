<?php include('db_connect.php');?>

<style>
    /* --- STYLE GIAO DIỆN ĐỒNG BỘ --- */
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Card chứa bảng */
    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }

    /* Header của Card */
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
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Nút Thêm Mới Gradient */
    .btn-gradient {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 10px 20px;
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

    /* Tùy chỉnh Table */
    .table-custom thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 15px;
        white-space: nowrap;
        font-size: 0.9rem;
    }

    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
        color: #444;
        font-size: 0.95rem;
    }

    .table-custom tbody tr:hover {
        background-color: #fcfcfc;
    }

    /* Badge cho số phòng */
    .badge-house {
        background: #e3f2fd;
        color: #1565c0;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Text tiền tệ */
    .text-price { color: #2e7d32; font-weight: 600; }
    .text-debt { color: #c62828; font-weight: 700; } /* Nợ màu đỏ đậm */
    .text-paid { color: #999; font-weight: 500; } /* Không nợ màu xám */

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
        margin: 0 2px;
        cursor: pointer;
    }
    .btn-view { background: #e0f2f1; color: #00897b; }
    .btn-view:hover { background: #00897b; color: white; }

    .btn-edit { background: #e3f2fd; color: #1976d2; }
    .btn-edit:hover { background: #1976d2; color: white; }

    .btn-delete { background: #ffebee; color: #c62828; }
    .btn-delete:hover { background: #c62828; color: white; }

    /* Style riêng cho nút In hợp đồng */
    .btn-print { background: #fff3cd; color: #856404; }
    .btn-print:hover { background: #ffeeba; color: #856404; }

</style>

<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h4 class="card-title">
                        <i class="fa fa-users text-primary"></i> Danh sách Khách thuê
                    </h4>
                    <button class="btn btn-gradient btn-sm" id="new_tenant">
                        <i class="fa fa-plus-circle"></i> Thêm khách mới
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Họ và tên</th>
                                <th class="text-center">Phòng thuê</th>
                                <th class="text-right">Giá thuê</th>
                                <th class="text-right">Dư nợ hiện tại</th>
                                <th class="text-center">Lần đóng cuối</th>
                                <th class="text-center" width="185">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            $tenant = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.status = 1 order by h.house_no desc ");
                            while($row=$tenant->fetch_assoc()):
                                // Logic tính toán công nợ
                                $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
                                $months = floor(($months) / (30*60*60*24));
                                $payable = $row['price'] * $months;

                                $paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
                                $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;

                                $last_payment_query = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
                                $last_payment_data = $last_payment_query->num_rows > 0 ? $last_payment_query->fetch_array()['date_created'] : null;
                                $last_payment_display = $last_payment_data ? date("d/m/Y", strtotime($last_payment_data)) : '<span class="text-muted small">Chưa đóng</span>';

                                $outstanding = $payable - $paid;
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $i++ ?></td>

                                    <td>
                                        <b style="color: #333; font-size: 1rem;"><?php echo ucwords($row['name']) ?></b>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge-house"><?php echo $row['house_no'] ?></span>
                                    </td>

                                    <td class="text-right">
                                        <span class="text-price"><?php echo number_format($row['price'], 0, ',', '.') ?> VNĐ</span>
                                    </td>

                                    <td class="text-right">
                                        <?php if($outstanding > 0): ?>
                                            <span class="text-debt"><?php echo number_format($outstanding, 0, ',', '.') ?> VNĐ</span>
                                        <?php else: ?>
                                            <span class="text-paid">0 VNĐ</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php echo $last_payment_display ?>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn-action btn-view view_payment" type="button" data-id="<?php echo $row['id'] ?>" title="Xem lịch sử thanh toán">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn-edit edit_tenant" type="button" data-id="<?php echo $row['id'] ?>" title="Sửa thông tin">
                                            <i class="fa fa-pen"></i>
                                        </button>

                                        <button class="btn-action btn-print print_contract" type="button" data-id="<?php echo $row['id'] ?>" title="In hợp đồng thuê nhà">
                                            <i class="fa fa-file-contract"></i>
                                        </button>

                                        <button class="btn-action btn-delete delete_tenant" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa khách thuê">
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
    // Cấu hình DataTables tiếng Việt
    $('table').dataTable({
        "language": {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "info": "Hiện _START_ đến _END_ của _TOTAL_ khách",
            "paginate": {
                "next": '<i class="fa fa-chevron-right"></i>',
                "previous": '<i class="fa fa-chevron-left"></i>'
            }
        }
    })

    $('#new_tenant').click(function(){
        uni_modal("Thêm Khách Thuê Mới","manage_tenant.php","mid-large")
    })

    $('.view_payment').click(function(){
        uni_modal("Lịch sử Thanh Toán","view_payment.php?id="+$(this).attr('data-id'),"large")
    })

    $('.edit_tenant').click(function(){
        uni_modal("Cập nhật Thông Tin","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
    })

    // Sự kiện bấm nút In Hợp Đồng
    $('.print_contract').click(function(){
        var id = $(this).attr('data-id');
        // Mở file print_contract.php trong tab mới
        window.open("print_contract.php?id=" + id, "_blank");
    })

    $('.delete_tenant').click(function(){
        _conf("Bạn có chắc chắn muốn xóa khách thuê này?","delete_tenant",[$(this).attr('data-id')])
    })

    function delete_tenant($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_tenant',
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