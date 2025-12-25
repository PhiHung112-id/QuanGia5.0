<?php
include('../db_connect.php');
session_start();

// Kiểm tra đăng nhập. Chưa đăng nhập thì đá về trang login
if(!isset($_SESSION['login_customer_id'])){
    header("location:login.php");
    exit;
}

$id = $_SESSION['login_customer_id'];

// Lấy thông tin khách hàng
$user = $conn->query("SELECT * FROM customers WHERE id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hồ sơ của tôi | Quản Gia 5.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        :root { --primary: #4e73df; --bg-light: #f8f9fc; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg-light); color: #5a5c69; }
        .navbar-custom { background: white; box-shadow: 0 4px 30px rgba(0,0,0,0.03); }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header-profile { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; padding: 30px; text-align: center; }
        .avatar-circle { width: 80px; height: 80px; background: white; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 15px; border: 4px solid rgba(255,255,255,0.3); }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .bg-pending { background: #fff3cd; color: #856404; }
        .bg-confirmed { background: #d4edda; color: #155724; }
        .bg-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold text-dark" href="index.php">
            <i class="fa fa-arrow-left mr-2 text-primary"></i> Quay lại trang chủ
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card card-custom">
                <div class="card-header-profile">
                    <div class="avatar-circle">
                        <i class="fa fa-user"></i>
                    </div>
                    <h5 class="font-weight-bold"><?php echo $user['name'] ?></h5>
                    <p class="mb-0 small opacity-75"><?php echo $user['email'] ?></p>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Số điện thoại</label>
                        <div class="font-weight-bold text-dark"><?php echo $user['phone'] ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Địa chỉ</label>
                        <div class="font-weight-bold text-dark"><?php echo $user['address'] ?></div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="../ajax.php?action=logout_customer" class="btn btn-outline-danger rounded-pill px-4 btn-sm">
                            <i class="fa fa-sign-out-alt mr-2"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h4 class="font-weight-bold mb-4 text-dark">Lịch sử đặt phòng</h4>

            <?php
            $bookings = $conn->query("SELECT b.*, h.house_no, h.price, h.img_path, c.name as cat_name 
                                      FROM bookings b 
                                      INNER JOIN houses h ON h.id = b.house_id 
                                      INNER JOIN categories c ON c.id = h.category_id 
                                      WHERE b.customer_id = $id 
                                      ORDER BY b.id DESC");

            if($bookings->num_rows > 0):
                while($row = $bookings->fetch_assoc()):
                    $status = $row['status'];
                    $img = !empty($row['img_path']) && file_exists('../assets/uploads/'.$row['img_path']) ? '../assets/uploads/'.$row['img_path'] : '../assets/uploads/no-image.jpg';
                    ?>
                    <div class="card card-custom mb-3">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-3 col-md-2">
                                    <img src="<?php echo $img ?>" class="rounded" style="width: 100%; height: 70px; object-fit: cover;">
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="font-weight-bold mb-1 text-primary">Phòng <?php echo $row['house_no'] ?></h5>
                                            <p class="text-muted small mb-1"><?php echo $row['cat_name'] ?> - <?php echo number_format($row['price']) ?>đ/tháng</p>
                                        </div>
                                        <div class="text-right">
                                            <?php if($status == 0): ?>
                                                <span class="status-badge bg-pending">Chờ duyệt</span>
                                            <?php elseif($status == 1): ?>
                                                <span class="status-badge bg-confirmed">Đã duyệt</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-cancelled">Đã hủy</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="view.php?id=<?php echo $row['house_id'] ?>" class="text-muted small" style="text-decoration: underline;">Xem chi tiết phòng</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-folder-open fa-3x mb-3 text-gray-300"></i>
                    <p>Bạn chưa đặt phòng nào cả.</p>
                    <a href="index.php" class="btn btn-primary btn-sm rounded-pill">Tìm phòng ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>