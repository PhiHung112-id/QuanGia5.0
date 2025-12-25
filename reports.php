<?php
include 'db_connect.php';
?>

<style>
    /* --- STYLE GIAO DIỆN BÁO CÁO --- */
    .container-fluid {
        font-family: 'Poppins', sans-serif;
    }

    /* Tiêu đề trang */
    .page-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .page-header h4 {
        font-weight: 700;
        color: #333;
    }

    /* Style cho Card Báo cáo */
    .report-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        position: relative;
        height: 100%;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 30px;
    }

    /* Hiệu ứng khi rê chuột */
    .report-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    /* Icon tròn lớn */
    .icon-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
        transition: transform 0.3s;
    }

    .report-card:hover .icon-container {
        transform: scale(1.1) rotate(5deg);
    }

    /* Màu sắc riêng cho từng loại */
    .style-payment .icon-container {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1976d2;
    }
    .style-balance .icon-container {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        color: #f57c00;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .card-desc {
        color: #777;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    /* Nút xem ngay */
    .btn-view {
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        transition: margin 0.3s;
    }
    .style-payment .btn-view { color: #1976d2; }
    .style-balance .btn-view { color: #f57c00; }

    .report-card:hover .btn-view {
        margin-left: 10px; /* Hiệu ứng chạy chữ khi hover */
    }

</style>

<div class="container-fluid p-4">

    <div class="page-header">
        <h4><i class="fa fa-chart-pie text-primary mr-2"></i> Tổng hợp Báo cáo</h4>
        <small class="text-muted">Chọn loại báo cáo bạn muốn xem chi tiết</small>
    </div>

    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card report-card style-payment" onclick="location.href='index.php?page=payment_report'">
                <div class="d-flex align-items-center">
                    <div class="icon-container mr-4">
                        <i class="fa fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Báo cáo Doanh thu</h5>
                        <p class="card-desc">Thống kê chi tiết các khoản đã thu, lịch sử thanh toán theo tháng.</p>
                        <span class="btn-view">
                         Xem báo cáo <i class="fa fa-arrow-right ml-2"></i>
                     </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card report-card style-balance" onclick="location.href='index.php?page=balance_report'">
                <div class="d-flex align-items-center">
                    <div class="icon-container mr-4">
                        <i class="fa fa-balance-scale"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Báo cáo Dư nợ</h5>
                        <p class="card-desc">Theo dõi tình trạng công nợ, số tiền khách còn thiếu, tiền cọc.</p>
                        <span class="btn-view">
                         Xem báo cáo <i class="fa fa-arrow-right ml-2"></i>
                     </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>