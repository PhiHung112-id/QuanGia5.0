<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM payments where id= ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $val){
        $$k=$val;
    }
} else {
    function generateCode() { return mt_rand(100000, 999999); }
    $code = generateCode();
    while($conn->query("SELECT * FROM payments where invoice = '$code'")->num_rows > 0){
        $code = generateCode();
    }
    $invoice = $code;
}
?>

<style>
    .container-fluid { font-family: 'Poppins', sans-serif; }
    .control-label { font-weight: 600; color: #555; font-size: 0.9rem; margin-bottom: 5px; }
    .form-control, .custom-select { border-radius: 8px; border: 1px solid #ddd; padding: 10px; height: auto; }
    .input-group-text { background: #f8f9fa; border-radius: 8px 0 0 8px; font-weight: bold; color: #555; border: 1px solid #ddd; }

    .tenant-details-box {
        background: #e3f2fd;
        border: 1px dashed #1976d2;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    .price-display { font-size: 1.1rem; font-weight: bold; color: #28a745; }
    .total-box { background: #fff3cd; border: 1px solid #ffeeba; border-radius: 10px; padding: 15px; }
</style>

<div class="container-fluid">
    <form action="" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Khách thuê phòng</label>
                    <select name="tenant_id" id="tenant_id" class="custom-select select2">
                        <option value=""></option>
                        <?php
                        // CẬP NHẬT SQL: Lấy thêm house_no để hiển thị
                        $tenant = $conn->query("SELECT t.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, h.house_no 
                                                FROM tenants t 
                                                INNER JOIN houses h ON h.id = t.house_id 
                                                WHERE t.status = 1 
                                                ORDER BY h.house_no ASC");
                        while($row=$tenant->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $tenant_id == $row['id'] ? 'selected' : '' ?>>
                                P.<?php echo $row['house_no'] ?> - <?php echo ucwords($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div id="details"></div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Mã Hóa đơn</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-primary">HD-</span>
                        </div>
                        <input type="text" class="form-control font-weight-bold" name="invoice" value="<?php echo isset($invoice) ? $invoice :'' ?>" readonly style="background-color: #f8f9fc;">
                        <?php if(!isset($_GET['id'])): ?>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="randomNewCode()" title="Tạo mã khác"><i class="fa fa-sync-alt"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">Mã tự động, không trùng lặp.</small>
                </div>
            </div>
        </div>

        <hr>
        <label class="control-label text-primary"><i class="fa fa-calculator"></i> Chi phí phát sinh tháng này:</label>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Tiền Điện</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-bolt text-warning"></i></span></div>
                        <input type="number" class="form-control text-right calculate-total" min="0" name="cost_electric" id="cost_electric" value="<?php echo isset($cost_electric) ? $cost_electric : 0 ?>">
                        <div class="input-group-append"><span class="input-group-text">đ</span></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Tiền Nước</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-tint text-info"></i></span></div>
                        <input type="number" class="form-control text-right calculate-total" min="0" name="cost_water" id="cost_water" value="<?php echo isset($cost_water) ? $cost_water : 0 ?>">
                        <div class="input-group-append"><span class="input-group-text">đ</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="total-box">
                    <label class="control-label" style="font-size: 1rem;">TỔNG THANH TOÁN (Tiền phòng + Điện + Nước)</label>
                    <div class="input-group">
                        <input type="number" class="form-control text-right" style="font-size: 1.5rem; font-weight: bold; color: #c62828; height: 50px;" name="amount" min="0" id="amount" value="<?php echo isset($amount) ? $amount :'' ?>" readonly>
                        <div class="input-group-append"><span class="input-group-text" style="font-size: 1.2rem; font-weight: bold;">VNĐ</span></div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<div id="details_clone" style="display: none">
    <div class='tenant-details-box'>
        <div class="d-flex justify-content-between">
            <span><i class="fa fa-user"></i> <b class="tname"></b></span>
            <span>Phòng: <b class="hno text-primary" style="font-size: 1.2rem;"></b></span>
        </div>
        <hr style="margin: 5px 0;">
        <div class="d-flex justify-content-between align-items-center">
            <span>Giá thuê cứng:</span>
            <span class="price-display"><span class="price"></span> đ</span>
        </div>
        <input type="hidden" class="hidden_rent_price" value="0">
    </div>
</div>

<script>
    $(document).ready(function(){
        if('<?php echo isset($id)? 1:0 ?>' == 1)
            $('#tenant_id').trigger('change')
    })

    $('.select2').select2({ placeholder:"Chọn khách thuê", width:"100%" })

    function randomNewCode() {
        var randomNum = Math.floor(Math.random() * (999999 - 100000 + 1) ) + 100000;
        $('input[name="invoice"]').val(randomNum);
    }

    function calculate_total(){
        var rent = parseFloat($('.hidden_rent_price').val()) || 0;
        var electric = parseFloat($('#cost_electric').val()) || 0;
        var water = parseFloat($('#cost_water').val()) || 0;
        var total = rent + electric + water;
        $('#amount').val(total);
    }

    $('.calculate-total').on('keyup change input', function(){
        calculate_total();
    });

    $('#tenant_id').change(function(){
        if($(this).val() <= 0) return false;

        start_load()
        $.ajax({
            url:'ajax.php?action=get_tdetails',
            method:'POST',
            data:{id:$(this).val(), pid:'<?php echo isset($id) ? $id : '' ?>'},
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp)
                    var details = $('#details_clone .tenant-details-box').clone()

                    // Điền thông tin cơ bản
                    details.find('.tname').text(resp.name)
                    details.find('.hno').text(resp.house_no)
                    details.find('.price').text(resp.price)

                    var rawPrice = String(resp.price).replace(/\./g, '').replace(/,/g, '');
                    details.find('.hidden_rent_price').val(rawPrice);
                    $('#details').html(details);

                    if($('#cost_electric').val() == 0 || $('#cost_electric').val() == ''){
                        $('#cost_electric').val(resp.cost_electric);
                    }
                    if($('#cost_water').val() == 0 || $('#cost_water').val() == ''){
                        $('#cost_water').val(resp.cost_water);
                    }

                    // Gọi hàm tính tổng ngay lập tức
                    calculate_total();
                }
            },
            complete:function(){ end_load() }
        })
    })

    // Tìm đoạn script xử lý submit form
    $('#manage-payment').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_payment',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Lưu dữ liệu thành công",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
                else if(resp==2){
                    alert_toast("Lỗi: Số tiền không được nhỏ hơn 0!",'warning')
                    end_load()
                }
                // -------------------------------------------
                else{
                    alert_toast("Có lỗi xảy ra",'danger')
                    end_load()
                }
            }
        })
    })
</script>