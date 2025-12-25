<?php
// SỬA: Thêm ../ để gọi đúng file kết nối từ thư mục cha
include('../db_connect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản Gia 5.0 | Tìm Không Gian Sống</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #858796;
            --bg-light: #f8f9fc;
            --text-dark: #2e2e2e;
            --success: #1cc88a;
            --danger: #e74a3b;
            --card-radius: 20px;
            --accent-color: #f6c23e;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: #5a5c69;
            overflow-x: hidden;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            padding: 10px 0;
            transition: 0.3s;
        }
        .brand-text { font-weight: 800; font-size: 1.6rem; color: var(--primary); letter-spacing: -0.5px; }

        .nav-link-custom {
            color: #555 !important;
            font-weight: 600;
            margin: 0 10px;
            position: relative;
            transition: 0.3s;
        }
        .nav-link-custom:hover { color: var(--primary) !important; }
        .nav-link-custom::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
            background-color: var(--primary); transition: width .3s;
        }
        .nav-link-custom:hover::after { width: 100%; }

        .btn-login {
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.2);
            transition: 0.3s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3); }

        .hero-wrap {
            position: relative;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            /* SỬA: Thêm ../ vào đường dẫn ảnh banner để lùi ra thư mục gốc */
            background: linear-gradient(rgba(0, 40, 120, 0.6), rgba(0, 40, 120, 0.4)), url('../assets/uploads/banner.jpg');
            background-size: cover; background-position: center;
            height: 480px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 0 0 60px 60px;
            margin-bottom: 60px;
        }

        .hero-content { text-align: center; color: white; width: 100%; max-width: 850px; padding: 20px; z-index: 2; }
        .hero-title { font-size: 3.2rem; font-weight: 700; margin-bottom: 1.5rem; text-shadow: 0 4px 10px rgba(0,0,0,0.2); line-height: 1.2; }

        .search-container {
            background: white;
            padding: 10px;
            border-radius: 50px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .search-input { border: none; padding: 15px 25px; width: 100%; font-weight: 500; outline: none; color: #444; background: transparent; }
        .search-divider { width: 1px; height: 30px; background: #eee; margin: 0 10px; }
        .btn-search {
            border-radius: 40px; padding: 12px 40px; font-weight: 600;
            background: linear-gradient(45deg, #f6c23e, #f4b619);
            border: none; color: white; white-space: nowrap; transition: 0.3s;
        }
        .btn-search:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(246, 194, 62, 0.4); }

        .room-card {
            border: none; border-radius: var(--card-radius); background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%; overflow: hidden; position: relative;
        }
        .room-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .room-img-wrap { position: relative; height: 240px; overflow: hidden; }
        .room-img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s ease; }
        .room-card:hover .room-img { transform: scale(1.08); }
        .room-badge {
            position: absolute; top: 15px; left: 15px; padding: 6px 16px; border-radius: 30px;
            font-size: 0.8rem; font-weight: 600; color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15); backdrop-filter: blur(4px);
        }
        .bg-available { background: rgba(28, 200, 138, 0.9); }
        .bg-occupied { background: rgba(231, 74, 59, 0.9); }
        .room-body { padding: 25px; position: relative; }
        .room-cat { color: var(--primary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .room-title { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; transition: 0.2s; }
        .room-card:hover .room-title { color: var(--primary); }
        .room-price { font-size: 1.4rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; }
        .room-price small { font-size: 0.9rem; font-weight: 400; color: #888; margin-left: 5px; }
        .btn-view-detail {
            margin-top: 15px; width: 100%; border-radius: 12px; background: #f1f5f9;
            color: #444; font-weight: 600; padding: 10px; border: none; transition: 0.2s;
        }
        .room-card:hover .btn-view-detail { background: var(--primary); color: white; }

        .empty-state { text-align: center; padding: 60px 20px; opacity: 0.7; }
        .empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 20px; }

        .footer-link { color: #5a5c69; text-decoration: none; display: block; margin-bottom: 10px; transition: 0.3s; }
        .footer-link:hover { color: var(--primary); padding-left: 5px; }

        @media (max-width: 768px) {
            .hero-wrap { height: 400px; border-radius: 0 0 30px 30px; }
            .hero-title { font-size: 2rem; }
            .search-container { flex-direction: column; border-radius: 20px; padding: 20px; }
            .search-divider { display: none; }
            .search-input { border-bottom: 1px solid #eee; margin-bottom: 10px; padding: 10px; }
            .btn-search { width: 100%; margin-top: 10px; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="fa fa-laptop-house fa-lg text-primary mr-2"></i>
            <div>
                <span class="brand-text">QUẢN GIA</span>
                <span style="color: var(--accent-color); font-weight: 800; font-size: 1.6rem;">5.0</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
            <i class="fa fa-bars text-secondary"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ml-auto mr-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="#list-rooms">Danh sách phòng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="#contact-us">Liên hệ</a>
                </li>
            </ul>

            <div class="">
                <?php if(isset($_SESSION['login_customer_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle btn-login" type="button" data-toggle="dropdown">
                            <i class="fa fa-user-circle mr-2"></i> <?php echo $_SESSION['login_customer_name'] ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2" style="border-radius: 15px; overflow: hidden;">
                            <a class="dropdown-item py-2" href="profile.php"><i class="fa fa-id-card mr-2 text-muted"></i> Hồ sơ của tôi</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item py-2 text-danger" href="../ajax.php?action=logout_customer"><i class="fa fa-sign-out-alt mr-2"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-login"><i class="fa fa-sign-in-alt mr-2"></i> Đăng nhập / Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="hero-wrap">
    <div class="hero-content">
        <h1 class="hero-title">Tìm Kiếm Không Gian Sống<br>Lý Tưởng Của Bạn</h1>
        <p class="mb-4" style="font-size: 1.1rem; opacity: 0.9;">Hệ thống phòng trọ hiện đại, an ninh và tiện nghi hàng đầu.</p>

        <form action="index.php" method="GET">
            <div class="search-container">
                <i class="fa fa-search text-muted ml-3"></i>
                <input type="text" class="search-input" name="keyword" placeholder="Nhập khu vực, tên phòng..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">

                <div class="search-divider"></div>

                <i class="fa fa-layer-group text-muted ml-2"></i>
                <select class="search-input" name="cat" style="cursor: pointer;">
                    <option value="">Tất cả loại phòng</option>
                    <?php
                    $cat = $conn->query("SELECT * FROM categories order by name asc");
                    while($row=$cat->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['cat']) && $_GET['cat'] == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>

                <button class="btn btn-search shadow">Tìm Kiếm</button>
            </div>
        </form>
    </div>
</div>

<div class="container py-5" id="list-rooms" style="margin-top: -80px; position: relative; z-index: 5;">
    <div class="row">
        <?php
        $where = "";
        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) $where .= " AND (h.house_no LIKE '%".$_GET['keyword']."%' OR h.description LIKE '%".$_GET['keyword']."%') ";
        if(isset($_GET['cat']) && !empty($_GET['cat'])) $where .= " AND h.category_id = ".$_GET['cat'];

        $houses = $conn->query("SELECT h.*, c.name as cname FROM houses h INNER JOIN categories c ON c.id = h.category_id WHERE 1=1 $where ORDER BY h.id ASC");

        if($houses->num_rows > 0):
            while($row = $houses->fetch_assoc()):
                $check = $conn->query("SELECT * FROM tenants WHERE house_id = {$row['id']} AND status = 1")->num_rows;
                $status = ($check > 0) ? 'occupied' : 'available';
                $status_text = ($check > 0) ? 'Đã thuê' : 'Còn trống';

                // --- SỬA LỖI ẢNH Ở ĐÂY ---
                // Thêm ../ vào trước assets để trỏ ra thư mục gốc
                $img = !empty($row['img_path']) && file_exists('../assets/uploads/'.$row['img_path']) ? '../assets/uploads/'.$row['img_path'] : '../assets/uploads/no-image.jpg';
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="view.php?id=<?php echo $row['id'] ?>" class="text-decoration-none text-dark">
                        <div class="room-card">
                            <div class="room-img-wrap">
                            <span class="room-badge bg-<?php echo $status ?>">
                                <?php if($status == 'available'): ?>
                                    <i class="fa fa-check-circle mr-1"></i>
                                <?php else: ?>
                                    <i class="fa fa-lock mr-1"></i>
                                <?php endif; ?>
                                <?php echo $status_text ?>
                            </span>
                                <img src="<?php echo $img ?>" class="room-img" alt="<?php echo $row['house_no'] ?>">
                            </div>

                            <div class="room-body">
                                <div class="room-cat"><?php echo $row['cname'] ?></div>
                                <h5 class="room-title">Phòng <?php echo $row['house_no'] ?></h5>
                                <p class="text-muted small mb-3" style="height: 42px; overflow: hidden; line-height: 1.5;">
                                    <?php echo $row['description'] ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="room-price">
                                        <?php echo number_format($row['price']) ?>đ <small>/ tháng</small>
                                    </div>
                                </div>

                                <button class="btn-view-detail">
                                    Xem chi tiết <i class="fa fa-arrow-right ml-1" style="font-size: 0.8rem"></i>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            <?php
            endwhile;
        else:
            ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fa fa-search-minus"></i>
                    <h4>Không tìm thấy phòng phù hợp</h4>
                    <p>Hãy thử tìm kiếm với từ khóa khác hoặc chọn "Tất cả loại phòng"</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer style="background: white; padding-top: 60px; border-top: 1px solid #eee; margin-top: 50px;" id="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fa fa-laptop-house text-primary mr-2"></i> QUẢN GIA 5.0
                </h5>
                <p class="text-muted small" style="line-height: 1.8;">
                    Chúng tôi cung cấp giải pháp tìm kiếm và quản lý nhà trọ thông minh, an toàn và tiện lợi nhất cho sinh viên và người đi làm.
                </p>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="font-weight-bold text-dark mb-3">Liên Kết Nhanh</h5>
                <a href="index.php" class="footer-link"><i class="fa fa-angle-right mr-2"></i> Trang chủ</a>
                <a href="#list-rooms" class="footer-link"><i class="fa fa-angle-right mr-2"></i> Danh sách phòng</a>
                <a href="login.php" class="footer-link"><i class="fa fa-angle-right mr-2"></i> Đăng nhập / Đăng ký</a>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="font-weight-bold text-dark mb-3">Thông Tin Liên Hệ</h5>
                <div class="mb-2">
                    <i class="fa fa-map-marker-alt text-primary mr-2" style="width: 20px;"></i>
                    <span class="text-muted">123 Đường ABC, P. Phú Cường, TP. Thủ Dầu Một</span>
                </div>
                <div class="mb-2">
                    <i class="fa fa-phone text-primary mr-2" style="width: 20px;"></i>
                    <span class="text-muted">0988.888.888</span>
                </div>
                <div class="mb-2">
                    <i class="fa fa-envelope text-primary mr-2" style="width: 20px;"></i>
                    <span class="text-muted">admin@quangia50.vn</span>
                </div>
                <div class="mt-3">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-circle mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-danger rounded-circle mr-2"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-info rounded-circle"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <div class="text-center border-top pt-4 pb-4 mt-4">
            <p class="text-muted small mb-0">
                &copy; 2025 Developed with <i class="fa fa-heart text-danger"></i> by Hung
            </p>
        </div>
    </div>
</footer>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>