<?php include 'db_connect.php' ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --font-primary: 'Poppins', sans-serif;
        --radius: 20px;
    }

    body {
        background-color: #f3f6f9;
        font-family: var(--font-primary);
    }

    .dashboard-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 1.5rem;
    }
    .dashboard-subtitle {
        color: #95a5a6;
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    /* --- STYLE CARD THỐNG KÊ (ĐÃ SỬA LỖI LỆCH CHIỀU CAO) --- */
    .summary-card {
        border: none;
        border-radius: var(--radius);
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 180px; /* Tăng chiều cao tối thiểu lên chút */
        height: 100%;      /* QUAN TRỌNG: Ép chiều cao bằng nhau */
        display: flex;     /* QUAN TRỌNG: Dùng Flexbox */
        flex-direction: column;
        justify-content: center; /* Căn giữa nội dung theo chiều dọc */
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        cursor: pointer;
    }

    .summary-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .bg-grad-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-grad-2 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-grad-3 { background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); }

    .card-content {
        position: relative;
        z-index: 2;
        padding: 25px;
        width: 100%; /* Đảm bảo nội dung chiếm hết chiều ngang */
    }

    .summary-num {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
        line-height: 1;
        text-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .summary-label {
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 8rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        transition: 0.3s;
        z-index: 1;
    }

    .summary-card:hover .icon-bg {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.2;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(5px);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* --- STYLE CARD BIỂU ĐỒ --- */
    .chart-card {
        border: none;
        border-radius: var(--radius);
        background: white;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.03);
        height: 100%; /* Ép chiều cao thẻ biểu đồ bằng nhau nếu nằm cùng hàng */
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #34495e;
        position: relative;
        padding-left: 15px;
    }

    .chart-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 20px;
        background: #4e73df;
        border-radius: 10px;
    }
</style>

<div class="container-fluid p-4">

    <div class="row">
        <div class="col-12">
            <h3 class="dashboard-title">Tổng quan hệ thống</h3>
            <p class="dashboard-subtitle">Chào mừng trở lại! Dưới đây là báo cáo hoạt động kinh doanh của bạn.</p>
        </div>
    </div>

    <div class="row d-flex align-items-stretch">

        <div class="col-md-4 mb-4">
            <div class="card summary-card bg-grad-1" onclick="location.href='index.php?page=houses'">
                <div class="card-content">
                    <div class="icon-circle"><i class="fa fa-home"></i></div>
                    <div class="summary-num"><?php echo $conn->query("SELECT * FROM houses")->num_rows ?></div>
                    <div class="summary-label">Nhà / Phòng</div>
                </div>
                <i class="fa fa-building icon-bg"></i>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card summary-card bg-grad-2" onclick="location.href='index.php?page=tenants'">
                <div class="card-content">
                    <div class="icon-circle"><i class="fa fa-users"></i></div>
                    <div class="summary-num"><?php echo $conn->query("SELECT * FROM tenants where status = 1 ")->num_rows ?></div>
                    <div class="summary-label">Khách đang thuê</div>
                </div>
                <i class="fa fa-user-check icon-bg"></i>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card summary-card bg-grad-3" onclick="location.href='index.php?page=payment_report'">
                <div class="card-content">
                    <div class="icon-circle"><i class="fa fa-wallet"></i></div>
                    <div class="summary-num" style="font-size: 1.8rem;">
                        <?php
                        $payment = $conn->query("SELECT sum(amount) as paid FROM payments where date_format(date_created, '%Y-%m') = '".date('Y-m')."' ");
                        echo $payment->num_rows > 0 ? number_format($payment->fetch_array()['paid']) : 0;
                        ?> <small style="font-size: 1rem">đ</small>
                    </div>
                    <div class="summary-label">Doanh thu tháng <?php echo date('m') ?></div>
                </div>
                <i class="fa fa-coins icon-bg"></i>
            </div>
        </div>
    </div>

    <?php
    // --- XỬ LÝ DỮ LIỆU PHP ---
    $revenue_data = [];
    $months_labels = [];
    for ($i = 5; $i >= 0; $i--) {
        $month_db = date('Y-m', strtotime("-$i months"));
        $month_show = "T".date('m', strtotime("-$i months"));

        $query = $conn->query("SELECT sum(amount) as total FROM payments WHERE date_format(date_created, '%Y-%m') = '$month_db'");
        $row = $query->fetch_assoc();

        $revenue_data[] = $row['total'] ? $row['total'] : 0;
        $months_labels[] = $month_show;
    }

    $total_houses = $conn->query("SELECT * FROM houses")->num_rows;
    $active_tenants = $conn->query("SELECT * FROM tenants WHERE status = 1")->num_rows;
    $empty_houses = $total_houses - $active_tenants;
    if($empty_houses < 0) $empty_houses = 0;
    ?>

    <div class="row d-flex align-items-stretch">
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card chart-card">
                <div class="chart-header">
                    <span class="chart-title">Biểu đồ doanh thu 6 tháng</span>
                    <i class="fa fa-chart-bar text-muted"></i>
                </div>
                <div style="height: 350px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card chart-card">
                <div class="chart-header">
                    <span class="chart-title">Tỉ lệ lấp đầy</span>
                    <i class="fa fa-chart-pie text-muted"></i>
                </div>
                <div style="height: 250px; position: relative; margin-top: 20px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <div class="row">
                        <div class="col-6 border-right">
                            <h5 class="font-weight-bold mb-0 text-success"><?php echo $active_tenants ?></h5>
                            <small class="text-muted">Đang ở</small>
                        </div>
                        <div class="col-6">
                            <h5 class="font-weight-bold mb-0 text-warning"><?php echo $empty_houses ?></h5>
                            <small class="text-muted">Còn trống</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- CẤU HÌNH CHART DOANH THU ---
    var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    var gradientFill = ctxRevenue.createLinearGradient(0, 0, 0, 400);
    gradientFill.addColorStop(0, 'rgba(78, 115, 223, 0.9)');
    gradientFill.addColorStop(1, 'rgba(78, 115, 223, 0.1)');

    var revenueChart = new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months_labels); ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?php echo json_encode($revenue_data); ?>,
                backgroundColor: gradientFill,
                borderColor: '#4e73df',
                borderWidth: 0,
                borderRadius: 8,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#333',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: "#f0f0f0" },
                    ticks: {
                        font: { family: "'Poppins', sans-serif", size: 11 },
                        callback: function(value) {
                            if(value >= 1000000) return (value/1000000) + ' Tr';
                            return value.toLocaleString('vi-VN');
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Poppins', sans-serif" } }
                }
            }
        }
    });

    // --- CẤU HÌNH CHART TRÒN ---
    var ctxStatus = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Đang thuê', 'Còn trống'],
            datasets: [{
                data: [<?php echo $active_tenants ?>, <?php echo $empty_houses ?>],
                backgroundColor: ['#1cc88a', '#f6c23e'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: "'Poppins', sans-serif" }
                    }
                }
            }
        }
    });
</script>