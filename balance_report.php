<?php include 'db_connect.php' ?>

<style>
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Card bao quanh */
    .card-report {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
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

    /* Tiêu đề */
    .report-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .report-subtitle {
        font-size: 0.9rem;
        color: #777;
        margin-top: 5px;
    }

    /* Nút in ấn */
    .btn-print {
        background: white;
        color: #28a745;
        border: 1px solid #28a745;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-print:hover {
        background: #28a745;
        color: white;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    /* Table */
    .table-custom {
        width: 100%;
        margin-bottom: 0;
    }

    .table-custom thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        border-top: none;
        border-bottom: 2px solid #eee;
        padding: 15px 10px;
        font-size: 0.9rem;
        white-space: nowrap; /* Không xuống dòng tiêu đề */
    }

    .table-custom tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
        color: #444;
        font-size: 0.95rem;
    }

    .table-custom tbody tr:hover {
        background-color: #fcfcfc;
    }

    /* Highlight tiền nợ */
    .debt-text {
        color: #dc3545; /* Màu đỏ */
        font-weight: 600;
    }
    .paid-text {
        color: #28a745; /* Màu xanh */
        font-weight: 600;
    }

    /* Ẩn hiện khi in */
    .on-print {
        display: none;
    }
</style>

<noscript>
    <style>
        .text-center { text-align:center; }
        .text-right { text-align:right; }
        table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
        tr,td,th { border:1px solid black; padding: 5px; font-size: 12px; }
        .report-header { text-align: center; margin-bottom: 20px; }
    </style>
</noscript>

<div class="container-fluid p-4">
    <div class="col-lg-12">
        <div class="card card-report">

            <div class="card-header-custom">
                <div>
                    <h4 class="report-title"><i class="fa fa-file-invoice-dollar text-warning mr-2"></i> Báo cáo Dư nợ & Thanh toán</h4>
                    <div class="report-subtitle">Dữ liệu tính đến tháng <?php echo date('m/Y') ?></div>
                </div>
                <button class="btn btn-print btn-sm" type="button" id="print">
                    <i class="fa fa-print mr-1"></i> In Báo Cáo
                </button>
            </div>

            <div class="card-body p-0">
                <div class="col-md-12">

                    <div id="report">
                        <div class="on-print report-header">
                            <h3>BÁO CÁO CÔNG NỢ PHÒNG TRỌ</h3>
                            <p>Ngày xuất báo cáo: <b><?php echo date('d/m/Y') ?></b></p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center" width="50">#</th>
                                    <th>Khách thuê</th>
                                    <th class="text-center">Phòng số</th>
                                    <th class="text-right">Giá thuê</th>
                                    <th class="text-center">Số tháng</th>
                                    <th class="text-right">Phải thu</th>
                                    <th class="text-right">Đã thu</th>
                                    <th class="text-right">Còn nợ</th>
                                    <th class="text-center">Lần đóng cuối</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                // Lấy danh sách khách thuê đang ở (status = 1)
                                $tenants =$conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.status = 1 order by h.house_no desc ");

                                if($tenants->num_rows > 0):
                                    while($row=$tenants->fetch_assoc()):
                                        // Tính số tháng đã ở
                                        $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
                                        $months = floor(($months) / (30*60*60*24)); // Giả sử 1 tháng = 30 ngày

                                        // Tính toán tiền
                                        $payable = $row['price'] * $months;

                                        // Lấy tổng tiền đã đóng
                                        $paid_query = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
                                        $paid = $paid_query->num_rows > 0 ? $paid_query->fetch_array()['paid'] : 0;

                                        // Lấy ngày đóng gần nhất
                                        $last_payment_query = $conn->query("SELECT * FROM payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
                                        $last_payment_data = $last_payment_query->num_rows > 0 ? $last_payment_query->fetch_array()['date_created'] : null;

                                        // Xử lý hiển thị ngày
                                        $last_payment_display = $last_payment_data ? date("d/m/Y", strtotime($last_payment_data)) : '<span class="text-muted">Chưa đóng</span>';

                                        // Tiền còn nợ
                                        $outstanding = $payable - $paid;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td style="font-weight: 500; color: #333;"><?php echo ucwords($row['name']) ?></td>
                                            <td class="text-center"><span class="badge badge-info"><?php echo $row['house_no'] ?></span></td>
                                            <td class="text-right"><?php echo number_format($row['price'],0,',','.') ?> đ</td>
                                            <td class="text-center"><?php echo $months ?> tháng</td>

                                            <td class="text-right"><?php echo number_format($payable,0,',','.') ?> đ</td>
                                            <td class="text-right paid-text"><?php echo number_format($paid,0,',','.') ?> đ</td>

                                            <td class="text-right <?php echo $outstanding > 0 ? 'debt-text' : '' ?>">
                                                <?php echo number_format($outstanding,0,',','.') ?> đ
                                            </td>

                                            <td class="text-center"><?php echo $last_payment_display ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <th colspan="9"><center>Không có dữ liệu.</center></th>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div> </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#print').click(function(){
        var _style = $('noscript').clone()
        var _content = $('#report').clone()
        // Hiển thị phần header báo cáo khi in
        _content.find('.on-print').show();

        var nw = window.open("","_blank","width=900,height=700");
        nw.document.write('<html><head><title>In Báo Cáo</title>');
        nw.document.write(_style.html());
        nw.document.write('</head><body>');
        nw.document.write(_content.html());
        nw.document.write('</body></html>');
        nw.document.close()
        nw.print()

        // Tự động đóng cửa sổ sau khi in xong (hoặc hủy)
        setTimeout(function(){
            nw.close()
        }, 500)
    })
</script>