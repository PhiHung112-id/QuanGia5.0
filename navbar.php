<style>
    /* --- 1. CẤU TRÚC CƠ BẢN (Giữ nguyên) --- */
    nav#sidebar {
        background: #ffffff;
        height: 100vh;
        overflow-y: auto;
        width: 250px;
        padding-top: 6rem; /* Né Topbar */
        padding-bottom: 60px;
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.05);
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: all 0.3s;
        border-right: 1px solid #f0f0f0;
    }

    /* Scrollbar đẹp */
    nav#sidebar::-webkit-scrollbar { width: 5px; }
    nav#sidebar::-webkit-scrollbar-track { background: transparent; }
    nav#sidebar::-webkit-scrollbar-thumb { background: #e2e6ea; border-radius: 10px; }
    nav#sidebar::-webkit-scrollbar-thumb:hover { background: #ced4da; }

    .menu-header {
        color: #adb5bd;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 15px 0 10px 25px;
    }

    /* Style nút Menu chung */
    .sidebar-list a.nav-item {
        position: relative;
        display: flex;
        align-items: center;
        padding: 12px 20px;
        margin: 4px 15px;
        border-radius: 12px;
        color: #555c65;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .icon-field {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        margin-right: 10px;
        background: rgba(0,0,0,0.05);
        border-radius: 50%;
        transition: all 0.3s;
        color: #6c757d;
    }

    /* Hover chung: Nền xám nhẹ */
    .sidebar-list a.nav-item:hover {
        background-color: #f8f9fc;
        transform: translateX(5px);
    }

    /* --- 2. CẤU HÌNH MÀU SẮC RIÊNG CHO TỪNG MỤC (PHẦN MỚI) --- */

    /* Khi Active chung: Chữ trắng, icon trong suốt */
    .sidebar-list a.nav-item.active {
        color: #ffffff !important;
    }
    .sidebar-list a.nav-item.active .icon-field {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    /* 2.1. TỔNG QUAN (Home) - Màu Xanh Dương (Blue) */
    .nav-home:hover { color: #4e73df; }
    .nav-home:hover .icon-field { color: #4e73df; background: #e8f0fe; }
    .nav-home.active {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
    }

    /* 2.2. DANH SÁCH PHÒNG (Houses) - Màu Xanh Cyan (Info) */
    .nav-houses:hover { color: #36b9cc; }
    .nav-houses:hover .icon-field { color: #36b9cc; background: #e0fcfc; }
    .nav-houses.active {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        box-shadow: 0 4px 15px rgba(54, 185, 204, 0.4);
    }

    /* 2.3. KHÁCH THUÊ (Tenants) - Màu Xanh Lá (Success) */
    .nav-tenants:hover { color: #1cc88a; }
    .nav-tenants:hover .icon-field { color: #1cc88a; background: #e3fcf3; }
    .nav-tenants.active {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        box-shadow: 0 4px 15px rgba(28, 200, 138, 0.4);
    }

    /* 2.4. SỰ CỐ (Maintenance) - Màu Đỏ (Danger) */
    .nav-maintenance:hover { color: #e74a3b; }
    .nav-maintenance:hover .icon-field { color: #e74a3b; background: #fceceb; }
    .nav-maintenance.active {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        box-shadow: 0 4px 15px rgba(231, 74, 59, 0.4);
    }

    /* 2.5. LOẠI PHÒNG (Categories) - Màu Tím (Indigo) */
    .nav-categories:hover { color: #6f42c1; }
    .nav-categories:hover .icon-field { color: #6f42c1; background: #f3e9fe; }
    .nav-categories.active {
        background: linear-gradient(135deg, #6f42c1 0%, #59359a 100%);
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.4);
    }

    /* 2.6. ĐẶT PHÒNG (Bookings) - Màu Cam (Warning) */
    .nav-bookings:hover { color: #f6c23e; }
    .nav-bookings:hover .icon-field { color: #f6c23e; background: #fff8e1; }
    .nav-bookings.active {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        box-shadow: 0 4px 15px rgba(246, 194, 62, 0.4);
    }

    /* 2.7. HÓA ĐƠN (Invoices) - Màu Teal (Tiền tệ) */
    .nav-invoices:hover { color: #20c997; }
    .nav-invoices:hover .icon-field { color: #20c997; background: #e6fffa; }
    .nav-invoices.active {
        background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
        box-shadow: 0 4px 15px rgba(32, 201, 151, 0.4);
    }

    /* 2.8. THỐNG KÊ (Reports) - Màu Hồng (Pink) */
    .nav-reports:hover { color: #e83e8c; }
    .nav-reports:hover .icon-field { color: #e83e8c; background: #fce4ec; }
    .nav-reports.active {
        background: linear-gradient(135deg, #e83e8c 0%, #c21768 100%);
        box-shadow: 0 4px 15px rgba(232, 62, 140, 0.4);
    }

    /* 2.10. GHI ĐIỆN NƯỚC (Utility Readings) - Màu Cam Đậm */
    .nav-utility_readings:hover { color: #fd7e14; }
    .nav-utility_readings:hover .icon-field { color: #fd7e14; background: #fff3e0; }
    .nav-utility_readings.active {
        background: linear-gradient(135deg, #fd7e14 0%, #d35400 100%);
        box-shadow: 0 4px 15px rgba(253, 126, 20, 0.4);
    }

    /* 2.9. ADMIN (Users) - Màu Xám Đậm (Secondary) */
    .nav-users:hover { color: #5a5c69; }
    .nav-users:hover .icon-field { color: #5a5c69; background: #eaecf4; }
    .nav-users.active {
        background: linear-gradient(135deg, #5a5c69 0%, #373840 100%);
        box-shadow: 0 4px 15px rgba(90, 92, 105, 0.4);
    }

</style>

<nav id="sidebar">
    <div class="sidebar-list">

        <div class="menu-header">Quản lý</div>

        <a href="index.php?page=home" class="nav-item nav-home">
            <span class='icon-field'><i class="fa fa-th-large"></i></span>
            Tổng quan
        </a>

        <a href="index.php?page=categories" class="nav-item nav-categories">
            <span class='icon-field'><i class="fa fa-layer-group"></i></span>
            Loại phòng
        </a>

        <a href="index.php?page=houses" class="nav-item nav-houses">
            <span class='icon-field'><i class="fa fa-door-open"></i></span>
            Danh sách phòng
        </a>

        <a href="index.php?page=tenants" class="nav-item nav-tenants">
            <span class='icon-field'><i class="fa fa-user-friends"></i></span>
            Khách thuê
        </a>

        <a href="index.php?page=maintenance" class="nav-item nav-maintenance">
            <span class='icon-field'><i class="fa fa-tools"></i></span>
            Sự cố & Sửa chữa</a>

        <a href="index.php?page=bookings" class="nav-item nav-bookings">
            <span class="icon-field"><i class="fa fa-calendar-check"></i></span> Danh sách đặt phòng
        </a>

        <div class="menu-header">Tài chính & Báo cáo</div>

        <a href="index.php?page=invoices" class="nav-item nav-invoices">
            <span class='icon-field'><i class="fa fa-file-invoice-dollar"></i></span>
            Hóa đơn & Thu tiền
        </a>

        <a href="index.php?page=utility_readings" class="nav-item nav-utility_readings">
            <span class='icon-field'><i class="fa fa-tachometer-alt"></i></span>
            Ghi Điện Nước
        </a>

        <a href="index.php?page=reports" class="nav-item nav-reports">
            <span class='icon-field'><i class="fa fa-chart-pie"></i></span>
            Thống kê doanh thu
        </a>

        <?php if($_SESSION['login_type'] == 1): ?>
            <div class="menu-header">Hệ thống</div>
            <a href="index.php?page=users" class="nav-item nav-users">
                <span class='icon-field'><i class="fa fa-user-shield"></i></span>
                Tài khoản Admin
            </a>
        <?php endif; ?>

        <div style="height: 50px;"></div>
    </div>
</nav>

<script>
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>