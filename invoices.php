<?php include('db_connect.php');?>

<style>
    /* --- STYLE GIAO DIỆN --- */
    .container-fluid { font-family: 'Poppins', sans-serif; }
    .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
    .card-header-custom { background: #fff; padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    .card-title { font-size: 1.2rem; font-weight: 700; color: #333; margin: 0; display: flex; align-items: center; gap: 10px; }
    .btn-gradient { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; border: none; padding: 10px 20px; border-radius: 50px; font-weight: 500; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); transition: all 0.3s; }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4); color: white; }
    .table-custom thead th { background-color: #f8f9fa; color: #555; font-weight: 600; border-top: none; border-bottom: 2px solid #eee; padding: 15px; white-space: nowrap; }
    .table-custom tbody td { padding: 15px; vertical-align: middle; border-top: 1px solid #f0f0f0; color: #444; font-size: 0.95rem; }
    .table-custom tbody tr:hover { background-color: #fcfcfc; }
    .invoice-code { font-family: 'Courier New', monospace; font-weight: 700; color: #007bff; background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-size: 0.9rem; }
    .amount-text { color: #28a745; font-weight: 700; }
    .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: all 0.2s; margin: 0 3px; }
    .btn-edit { background: #e3f2fd; color: #1976d2; }
    .btn-edit:hover { background: #1976d2; color: white; }
    .btn-delete { background: #ffebee; color: #c62828; }
    .btn-delete:hover { background: #c62828; color: white; }

    /* Style cho badge số phòng */
    .room-badge { background: #e0f2f1; color: #00695c; padding: 5px 10px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; border: 1px solid #b2dfdb; }
</style>

<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h4 class="card-title">
                        <i class="fa fa-file-invoice-dollar text-primary"></i> Danh sách Hóa đơn / Thanh toán
                    </h4>
                    <button class="btn btn-gradient btn-sm" id="new_invoice">
                        <i class="fa fa-plus-circle"></i> Thêm hóa đơn mới
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Ngày TT</th> <th>Khách thuê</th>
                                <th>Phòng</th> <th>Mã Hóa đơn</th>
                                <th class="text-right">Số tiền</th>
                                <th class="text-center" width="120">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            // SỬA CÂU SQL: Join thêm bảng houses (h) để lấy house_no
                            $invoices = $conn->query("SELECT p.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, h.house_no 
                                                      FROM payments p 
                                                      INNER JOIN tenants t ON t.id = p.tenant_id 
                                                      INNER JOIN houses h ON h.id = t.house_id 
                                                      WHERE t.status = 1 
                                                      ORDER BY date(p.date_created) DESC");
                            while($row=$invoices->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $i++ ?></td>

                                    <td>
                                        <?php echo date('d/m/Y',strtotime($row['date_created'])) ?>
                                    </td>

                                    <td>
                                        <div style="font-weight: 600; color: #333;">
                                            <i class="fa fa-user-circle text-secondary mr-1"></i>
                                            <?php echo ucwords($row['name']) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="room-badge">
                                            P.<?php echo $row['house_no'] ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="invoice-code">
                                            <?php echo ucwords($row['invoice']) ?>
                                        </span>
                                    </td>

                                    <td class="text-right">
                                        <span class="amount-text">
                                            <?php echo number_format($row['amount'], 0, ',', '.') ?> VNĐ
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn-action btn-edit edit_invoice" type="button" data-id="<?php echo $row['id'] ?>" title="Sửa / Xem chi tiết">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button class="btn-action btn-delete delete_invoice" type="button" data-id="<?php echo $row['id'] ?>" title="Xóa">
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
    $('table').dataTable({
        "language": {
            "search": "Tìm kiếm:",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "info": "Hiện _START_ đến _END_ của _TOTAL_ hóa đơn",
            "paginate": { "next": '>', "previous": '<' }
        }
    })

    $('#new_invoice').click(function(){
        uni_modal("Thêm hóa đơn mới","manage_payment.php","mid-large")
    })

    $('.edit_invoice').click(function(){
        uni_modal("Chi tiết hóa đơn","manage_payment.php?id="+$(this).attr('data-id'),"mid-large")
    })

    $('.delete_invoice').click(function(){
        _conf("Bạn có chắc chắn muốn xóa hóa đơn này không?","delete_invoice",[$(this).attr('data-id')])
    })

    function delete_invoice($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_payment',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Xóa dữ liệu thành công",'success')
                    setTimeout(function(){ location.reload() },1500)
                }
            }
        })
    }
</script>