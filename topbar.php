<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --accent-color: #f6c23e;
        --text-shimmer-gradient: linear-gradient(to right, #ffffff 0%, #f6c23e 45%, #ffffff 90%);
    }

    /* 1. Navbar */
    .navbar-custom {
        background: var(--primary-gradient);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        padding: 0.5rem 1.5rem;
        min-height: 5rem; /* Tăng chiều cao một chút cho thoáng */
        font-family: 'Poppins', sans-serif;
    }

    /* 2. Logo Container - Khung chứa */
    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 130px; /* Mở rộng chiều ngang để chứa hết logo liền mạch */
        height: 65px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding-left: 5px;
    }

    .logo-container:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(78, 115, 223, 0.3);
        border-color: #fff;
    }

    /* --- SVG LOGO ANIMATION (PHẦN HÌNH) --- */
    .logo-svg-combined {
        width: 100%;
        height: 100%;
    }

    /* Class chung cho các nét vẽ */
    .drawn-path {
        stroke-linecap: round;
        stroke-linejoin: round;
        fill: transparent;
        stroke-dasharray: 400;
        stroke-dashoffset: 400;
        animation: drawAndFill 5s ease-in-out infinite;
    }

    /* Nét chính (QG) - Màu trắng */
    .path-main {
        stroke: white;
        stroke-width: 3px;
    }

    /* Nét phụ (Số 5.0 và đường lượn sóng kết nối) - Màu vàng */
    .path-accent {
        stroke: var(--accent-color);
        stroke-width: 2px;
        animation-delay: 0.5s; /* Vẽ chậm hơn một nhịp */
    }

    /* Keyframes: Vẽ nét -> Tô màu -> Mờ đi */
    @keyframes drawAndFill {
        0% { stroke-dashoffset: 400; fill: transparent; }
        40% { stroke-dashoffset: 0; fill: transparent; }
        60% { stroke-dashoffset: 0; fill: var(--fill-color); } /* Sử dụng biến màu fill */
        80% { stroke-dashoffset: 0; fill: var(--fill-color); opacity: 1; }
        100% { stroke-dashoffset: 0; fill: var(--fill-color); opacity: 0; }
    }

    /* Hover State cho Logo hình */
    .logo-container:hover .path-main {
        stroke: #4e73df; --fill-color: #4e73df; /* Chuyển xanh */
    }
    .logo-container:hover .path-accent {
        stroke: #4e73df; --fill-color: #4e73df; /* Chuyển xanh */
    }
    /* Màu tô mặc định khi chưa hover */
    .path-main { --fill-color: white; }
    .path-accent { --fill-color: var(--accent-color); }


    /* --- TEXT ANIMATION (PHẦN CHỮ BÊN CẠNH) --- */
    .brand-text-animated {
        font-weight: 800; /* Chữ cực đậm */
        font-size: 1.5rem;
        text-transform: uppercase;
        text-decoration: none !important;
        line-height: 1;
        letter-spacing: 1px;

        /* Kỹ thuật tạo màu Gradient động cho chữ */
        background: var(--text-shimmer-gradient);
        background-size: 200% auto;
        color: transparent; /* Ẩn màu gốc */
        -webkit-background-clip: text; /* Cắt nền theo hình chữ */
        background-clip: text;

        /* Animation quét sáng */
        animation: textShimmer 6s linear infinite reverse;
    }

    @keyframes textShimmer {
        to { background-position: 200% center; }
    }

    .brand-subtitle {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
        font-weight: 500;
        letter-spacing: 2px;
        display: block;
        margin-top: 2px;
    }


    /* User Profile & Dropdown */
    .user-profile { color: white !important; font-weight: 600; padding: 8px 20px; border-radius: 30px; transition: 0.3s; background: rgba(255,255,255,0.15); display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(5px); }
    .user-profile:hover { background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.5); text-decoration: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .dropdown-menu-custom { border: none; border-radius: 15px; box-shadow: 0 15px 50px rgba(0,0,0,0.25); margin-top: 15px; overflow: hidden; padding: 0; min-width: 240px; }
    .dropdown-header { background: #f8f9fc; font-weight: 800; color: #4e73df; padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    .dropdown-item { padding: 12px 20px; font-size: 0.95rem; transition: 0.2s; border-bottom: 1px solid #f8f9fc; display: flex; align-items: center; font-weight: 500; color: #555; }
    .dropdown-item:hover { background-color: #f0f4ff; color: #4e73df; padding-left: 28px; }
    .icon-box { width: 30px; display: flex; justify-content: center; margin-right: 12px; }
</style>

<script src="https://cdn.lordicon.com/lordicon.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container-fluid">

        <div class="d-flex align-items-center">
            <div class="logo-container">
                <svg class="logo-svg-combined" viewBox="0 0 150 70">
                    <path class="drawn-path path-main" d="M35,15 A20,20 0 1,0 35,55 A20,20 0 1,0 35,15 M45,45 L57,60" />
                    <path class="drawn-path path-main" d="M95,25 A18,18 0 1,0 95,50 L95,35 L80,35" transform="translate(-10, 0)" />

                    <path class="drawn-path path-accent" d="M85,35 Q 95,55 130,55" fill="none" />

                    <text x="98" y="50" font-family="'Poppins', sans-serif" font-weight="800" font-size="16" class="drawn-path path-accent">5.0</text>
                </svg>
            </div>

            <div class="d-flex flex-column justify-content-center">
                <a class="brand-text-animated" href="#">
                    <?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : 'QUẢN GIA' ?>
                </a>
                <span class="brand-subtitle">Hệ thống quản lý 5.0</span>
            </div>
        </div>


        <div class="ml-auto">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle user-profile" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div style="margin-right: 12px; display: flex; align-items: center;">
                        <lord-icon
                                src="https://cdn.lordicon.com/kthelypq.json"
                                trigger="loop"
                                delay="2000"
                                colors="primary:#ffffff,secondary:#f6c23e"
                                style="width:34px;height:34px">
                        </lord-icon>
                    </div>
                    <?php echo $_SESSION['login_name'] ?>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-custom" aria-labelledby="account_settings">
                    <div class="dropdown-header text-center">BẢNG ĐIỀU KHIỂN</div>
                    <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account">
                        <div class="icon-box"><lord-icon src="https://cdn.lordicon.com/hwuyodym.json" trigger="morph" state="morph-spin" colors="primary:#4e73df,secondary:#f6c23e" style="width:24px;height:24px"></lord-icon></div>
                        Cài đặt tài khoản
                    </a>
                    <a class="dropdown-item text-danger font-weight-bold" href="ajax.php?action=logout" style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 15px;">
                        <div class="icon-box"><lord-icon src="https://cdn.lordicon.com/moscwhoj.json" trigger="hover" colors="primary:#e74a3b,secondary:#e74a3b" style="width:24px;height:24px"></lord-icon></div>
                        Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $('#manage_my_account').click(function(){
        uni_modal("Quản lý tài khoản","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
    })
</script>