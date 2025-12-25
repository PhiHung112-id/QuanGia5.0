<?php
include('../db_connect.php');
session_start();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $qry = $conn->query("SELECT h.*, c.name as cname FROM houses h INNER JOIN categories c ON c.id = h.category_id WHERE h.id = $id");
    foreach($qry->fetch_array() as $k => $val){ $$k=$val; }

    $check = $conn->query("SELECT * FROM tenants WHERE house_id = {$id} AND status = 1")->num_rows;
    $status = ($check > 0) ? 'occupied' : 'available';

    $gallery = [];

    if(!empty($img_path) && file_exists('../assets/uploads/'.$img_path)){
        $gallery[] = '../assets/uploads/'.$img_path;
    } else {
        $gallery[] = '../assets/uploads/no-image.jpg';
    }

    $extras = $conn->query("SELECT * FROM house_images WHERE house_id = $id");
    while($row_img = $extras->fetch_assoc()){
        if(!empty($row_img['img_path'])){
            $gallery[] = '../assets/uploads/'.$row_img['img_path'];
        }
    }

    $main_display_img = isset($gallery[0]) ? $gallery[0] : '../assets/uploads/no-image.jpg';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chi tiết <?php echo isset($house_no) ? $house_no : '' ?> | Quản Gia 5.0</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
        :root { --primary: #4e73df; --text-dark: #2e2e2e; --bg-light: #f8f9fc; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg-light); color: #5a5c69; }

        .navbar-custom { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 15px rgba(0,0,0,0.05); }

        .main-img-wrap {
            height: 450px; border-radius: 20px; overflow: hidden; position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 15px; background: #000;
        }
        .img-main { width: 100%; height: 100%; object-fit: contain; transition: 0.3s; }

        .thumb-row { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
        .thumb-img {
            width: 100px; height: 80px; object-fit: cover; border-radius: 10px; cursor: pointer;
            opacity: 0.5; transition: 0.3s; border: 2px solid transparent; flex-shrink: 0;
        }
        .thumb-img:hover { opacity: 1; }
        .thumb-img.active { opacity: 1; border-color: var(--primary); box-shadow: 0 0 10px rgba(78, 115, 223, 0.3); }

        .content-card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .section-title { font-weight: 700; color: var(--text-dark); margin-bottom: 20px; font-size: 1.2rem; }

        .amenity-item { display: flex; align-items: center; margin-bottom: 15px; color: #555; }
        .amenity-icon {
            width: 40px; height: 40px; background: #f0f2f5; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;
        }

        .booking-card {
            background: white; border-radius: 20px; padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: sticky; top: 100px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .price-large { font-size: 2rem; font-weight: 700; color: var(--text-dark); }

        .btn-book {
            width: 100%; border-radius: 12px; padding: 15px; font-weight: 700; font-size: 1.1rem;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3); transition: 0.3s; color: white;
        }
        .btn-book:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(78, 115, 223, 0.4); }

        .map-placeholder { width: 100%; height: 200px; background: #eee; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #aaa; text-align: center; }

        @media (max-width: 768px) {
            .main-img-wrap { height: 300px; }
            .booking-card { position: static; margin-top: 20px; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold text-dark" href="index.php">
            <i class="fa fa-arrow-left mr-2 text-primary"></i> Quay lại
        </a>
        <span class="navbar-text ml-auto font-weight-bold text-primary d-none d-md-block">QUẢN GIA 5.0</span>
    </div>
</nav>

<div class="container py-4">
    <?php if(isset($house_no)): ?>
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo $cname ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Phòng <?php echo $house_no ?></li>
                </ol>
            </nav>
            <h1 class="font-weight-bold text-dark">Phòng trọ cao cấp số <?php echo $house_no ?></h1>

            <p class="text-muted">
                <i class="fa fa-map-marker-alt mr-2 text-danger"></i>
                <?php echo !empty($location) ? $location : 'Chưa cập nhật địa chỉ cụ thể' ?>
            </p>
        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="main-img-wrap">
                    <span class="badge badge-<?php echo ($status=='available')?'success':'danger' ?> position-absolute m-3 px-3 py-2 rounded-pill" style="top:0; left:0; font-size:0.9rem; z-index:10;">
                        <?php echo ($status=='available') ? 'Đang trống' : 'Đã có người' ?>
                    </span>
                    <img src="<?php echo $main_display_img ?>" class="img-main" id="displayImg">
                </div>

                <?php if(count($gallery) > 0): ?>
                    <div class="thumb-row mb-4">
                        <?php
                        foreach($gallery as $key => $src):
                            $active_cls = ($key == 0) ? 'active' : '';
                            ?>
                            <img src="<?php echo $src ?>" class="thumb-img <?php echo $active_cls ?>" onclick="changeImg(this.src, this)">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="content-card">
                    <h4 class="section-title">Thông tin mô tả</h4>
                    <p style="line-height: 1.8; white-space: pre-wrap; color: #555;"><?php echo $description ?></p>
                </div>

                <div class="content-card">
                    <h4 class="section-title">Tiện ích có sẵn</h4>
                    <div class="row">
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-wifi"></i></div> Wifi tốc độ cao</div>
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-parking"></i></div> Bãi giữ xe rộng rãi</div>
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-video"></i></div> Camera an ninh 24/7</div>
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-clock"></i></div> Giờ giấc tự do</div>
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-bed"></i></div> Nội thất cơ bản</div>
                        <div class="col-md-6 amenity-item"><div class="amenity-icon"><i class="fa fa-broom"></i></div> Vệ sinh hành lang</div>
                    </div>
                </div>

                <div class="content-card">
                    <h4 class="section-title">Vị trí & Bản đồ</h4>
                    <p class="mb-3" style="font-size: 1rem;">
                        <strong>Địa chỉ:</strong> <?php echo !empty($location) ? $location : 'Đang cập nhật...' ?>
                    </p>

                    <?php if(!empty($map_link)): ?>
                        <div style="width: 100%; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <iframe
                                    src="<?php echo $map_link ?>"
                                    width="100%"
                                    height="450"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <p class="text-muted small mt-2 text-center">
                            <i class="fa fa-info-circle"></i> Nếu bản đồ không hiện, vui lòng kiểm tra lại link nhúng.
                        </p>
                    <?php else: ?>
                        <div class="map-placeholder">
                            <div><i class="fa fa-map-marker-slash fa-3x mb-3 text-secondary"></i><br>Chưa có dữ liệu bản đồ cho phòng này</div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="booking-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted small">Giá thuê từ</span>
                            <div class="price-large"><?php echo number_format($price, 0, ',', '.') ?>đ</div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-light text-secondary border">Tháng</span>
                        </div>
                    </div>

                    <hr>

                    <?php if($status == 'available'): ?>
                        <?php if(isset($_SESSION['login_customer_id'])): ?>
                            <button class="btn btn-primary btn-book mb-3" onclick="booking(<?php echo $id ?>)">
                                ĐẶT PHÒNG NGAY
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-primary btn-book mb-3">
                                ĐĂNG NHẬP ĐỂ ĐẶT
                            </a>
                        <?php endif; ?>

                        <div class="text-center text-muted small mb-3">Không tốn phí đặt cọc online</div>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-book mb-3" disabled>ĐÃ HẾT PHÒNG</button>
                    <?php endif; ?>

                    <div class="bg-light p-3 rounded mt-3 d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;">
                            <i class="fa fa-user-tie text-primary fa-lg"></i>
                        </div>
                        <div class="ml-3">
                            <div class="font-weight-bold">Chủ trọ (Admin)</div>
                            <div class="small text-muted">0988.888.888</div>
                        </div>
                        <a href="tel:0988888888" class="btn btn-sm btn-outline-success ml-auto rounded-circle"><i class="fa fa-phone"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h3 class="font-weight-bold mb-4">Có thể bạn cũng thích</h3>
            <div class="row">
                <?php
                $related = $conn->query("SELECT h.*, c.name as cname FROM houses h INNER JOIN categories c ON c.id = h.category_id WHERE h.category_id = $category_id AND h.id != $id LIMIT 3");
                if($related->num_rows > 0):
                    while($row = $related->fetch_assoc()):
                        $r_img = !empty($row['img_path']) && file_exists('../assets/uploads/'.$row['img_path']) ? '../assets/uploads/'.$row['img_path'] : '../assets/uploads/no-image.jpg';
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                                <img src="<?php echo $r_img ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="font-weight-bold">Phòng <?php echo $row['house_no'] ?></h6>
                                    <p class="text-muted small mb-2"><?php echo $row['cname'] ?></p>
                                    <div class="font-weight-bold text-primary"><?php echo number_format($row['price']) ?>đ</div>
                                    <a href="view.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-outline-primary btn-block mt-3 rounded-pill">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <div class="col-12 text-muted">Không có phòng tương tự nào khác.</div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <h3>Không tìm thấy phòng này!</h3>
            <a href="index.php" class="btn btn-primary rounded-pill mt-3">Quay lại trang chủ</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    function changeImg(src, element){
        document.getElementById('displayImg').src = src;
        $('.thumb-img').removeClass('active');
        $(element).addClass('active');
    }

    function booking(house_id){
        if(!confirm("Bạn chắc chắn muốn gửi yêu cầu đặt phòng này?")) return;

        var btn = $('.btn-book');
        btn.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

        $.ajax({
            url:'../ajax.php?action=save_booking',
            method:'POST',
            data:{ house_id: house_id, customer_id: '<?php echo isset($_SESSION['login_customer_id']) ? $_SESSION['login_customer_id'] : '' ?>' },
            success:function(resp){
                if(resp==1){
                    alert("🎉 Đặt phòng thành công! Chúng tôi sẽ liên hệ sớm.");
                    location.href='profile.php';
                }
                else if(resp==2){
                    alert("⚠️ Bạn đã đặt phòng này rồi, vui lòng chờ duyệt.");
                    btn.html('ĐẶT PHÒNG NGAY').prop('disabled', false);
                }
                else {
                    alert("Có lỗi xảy ra");
                    btn.html('ĐẶT PHÒNG NGAY').prop('disabled', false);
                }
            }
        })
    }
</script>
</body>
</html>