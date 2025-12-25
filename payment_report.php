<?php
// 1. SỬA LỖI KẾT NỐI ($conn)
// Kiểm tra xem biến $conn đã có chưa, nếu chưa thì mới include
if(!isset($conn)){
    include 'db_connect.php';
}

$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : date('Y-m');
?>

<style>
    /* GIỮ NGUYÊN CSS CŨ CỦA BẠN CHO ĐẸP */
    .container-fluid { font-family: 'Poppins', sans-serif; }
    .card-report { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
    .card-header-custom { background: #fff; padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    .report-title { font-size: 1.3rem; font-weight: 700; color: #333; margin: 0; }
    .filter-group { display: flex; align-items: center; background: #f8f9fa; padding: 5px 15px; border-radius: 50px; border: 1px solid #eee; }
    .filter-group label { margin: 0 10px 0 0; font-weight: 600; color: #555; }
    .filter-input { border: none; background: transparent; font-weight: 600; color: #007bff; outline: none; cursor: pointer; }
    .btn-gradient { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; border: none; padding: 8px 20px; border-radius: 50px; font-weight: 500; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); transition: all 0.3s; margin-left: 10px; }
    .btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4); color: white; }
    .btn-print { border: 1px solid #28a745; color: #28a745; background: white; padding: 8px 20px; border-radius: 50px; font-weight: 600; transition: 0.3s; }
    .btn-print:hover { background: #28a745; color: white; }
    .table-custom { width: 100%; margin-bottom: 0; }
    .table-custom thead th { background-color: #f8f9fa; color: #555; font-weight: 600; border-top: none; border-bottom: 2px solid #eee; padding: 15px; font-size: 0.9rem; }
    .table-custom tbody td { padding: 15px; vertical-align: middle; border-top: 1px solid #f0f0f0; color: #444; font-size: 0.95rem; }
    .table-custom tfoot th { background-color: #e8f5e9; color: #2e7d32; padding: 15px; font-weight: 700; font-size: 1.1rem; }
    .on-print { display: none; }
</style>

<noscript>
    <style>
        .text-center { text-align:center; }
        .text-right { text-align:right; }
        table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
        tr,td,th { border:1px solid black; padding: 8px; font-size: 12px; }
        .report-header { text-align: center; margin-bottom: 20px; }
        .total-row { background-color: #eee; font-weight: bold; }
    </style>
</noscript>

<div class="container-fluid p-4">
    <div class="col-lg-12">
        <div class="card card-report">
            <div class="card-header-custom">
                <h4 class="report-title">
                    <i class="fa fa-file-invoice text-primary mr-2"></i> Báo cáo Doanh thu
                </h4>
                <form id="filter-report" class="d-flex align-items-center" method="GET">
                    <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : 'reports' ?>">

                    <div class="filter-group">
                        <label>Chọn tháng:</label>
                        <input type="month" name="month_of" class="filter-input" value="<?php echo ($month_of) ?>">
                    </div>
                    <button class="btn btn-sm btn-gradient"><i class="fa fa-filter"></i> Lọc dữ liệu</button>
                    <button class="btn btn-sm btn-print ml-2" type="button" id="print"><i class="fa fa-print"></i> In Báo cáo</button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="col-md-12">
                    <div id="report">
                        <div class="on-print report-header">
                            <h3>BÁO CÁO DOANH THU THUÊ PHÒNG</h3>
                            <p>Tháng báo cáo: <b><?php echo date('m/Y',strtotime($month_of.'-1')) ?></b></p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center" width="50">#</th>
                                    <th>Ngày thu</th>
                                    <th>Khách thuê</th>
                                    <th class="text-center">Phòng</th>
                                    <th>Mã hóa đơn</th>
                                    <th class="text-right">Số tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                $tamount = 0;

                                // 2. SỬA LỖI SQL (QUAN TRỌNG)
                                // - Dùng LEFT JOIN để không mất tiền nếu khách bị xóa
                                // - Kiểm tra kỹ tên bảng 'payments' và cột 'invoice'

                                $sql = "SELECT p.*, 
                                               concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, 
                                               h.house_no 
                                        FROM payments p 
                                        LEFT JOIN tenants t ON t.id = p.tenant_id 
                                        LEFT JOIN houses h ON h.id = t.house_id 
                                        WHERE date_format(p.date_created,'%Y-%m') = '$month_of' 
                                        ORDER BY unix_timestamp(p.date_created) ASC";

                                $payments = $conn->query($sql);

                                if($payments->num_rows > 0 ):
                                    while($row=$payments->fetch_assoc()):
                                        $tamount += $row['amount'];
                                        // Xử lý tên: Nếu khách đã bị xóa (NULL) thì hiện 'Khách cũ / Đã xóa'
                                        $tenant_name = !empty($row['name']) ? ucwords($row['name']) : "<span class='text-danger'>Khách đã xóa</span>";
                                        $house_no = !empty($row['house_no']) ? $row['house_no'] : "N/A";

                                        // Xử lý mã hóa đơn: Kiểm tra cột 'invoice' hay 'invoice_no' trong database
                                        // Ở đây mình dùng $row['invoice'] theo code cũ của bạn
                                        $inv_code = isset($row['invoice']) ? $row['invoice'] : (isset($row['invoice_no']) ? $row['invoice_no'] : '');
                                        ?>
                                        <tr>
                                            <td class="text-center text-muted"><?php echo $i++ ?></td>
                                            <td><?php echo date('d/m/Y',strtotime($row['date_created'])) ?></td>
                                            <td><b><?php echo $tenant_name ?></b></td>
                                            <td class="text-center"><span class="badge badge-info"><?php echo $house_no ?></span></td>
                                            <td><span class="badge badge-light border"><?php echo $inv_code ?></span></td>
                                            <td class="text-right" style="font-weight: 600; color: #333;">
                                                <?php echo number_format($row['amount'], 0, ',', '.') ?> VNĐ
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <th colspan="6"><center class="py-4 text-muted">Không có dữ liệu thanh toán trong tháng <?php echo date('m/Y',strtotime($month_of.'-1')) ?>.</center></th>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">TỔNG DOANH THU THÁNG:</th>
                                    <th class="text-right"><?php echo number_format($tamount, 0, ',', '.') ?> VNĐ</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#print').click(function(){
        var _style = $('noscript').clone()
        var _content = $('#report').clone()
        _content.find('.on-print').show();
        var nw = window.open("","_blank","width=900,height=700");
        nw.document.write('<html><head><title>In Báo Cáo</title>');
        nw.document.write(_style.html());
        nw.document.write('</head><body>');
        nw.document.write(_content.html());
        nw.document.write('</body></html>');
        nw.document.close()
        nw.print()
        setTimeout(function(){ nw.close() },500)
    })


</script>