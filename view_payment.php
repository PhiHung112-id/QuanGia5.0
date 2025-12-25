<?php include 'db_connect.php' ?>

<?php
$tenants = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$_GET['id']} ");
foreach($tenants->fetch_array() as $k => $v){
    if(!is_numeric($k)){ $$k = $v; }
}
$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$_GET['id']);
$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
?>

<style>
    .container-fluid { font-family: 'Poppins', sans-serif; }
    .table-history thead th { background: #007bff; color: white; font-weight: normal; }
    .detail-row { font-size: 0.85rem; color: #666; font-style: italic; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="alert alert-info">
                <strong><i class="fa fa-user"></i> Khách:</strong> <?php echo ucwords($name) ?> <br>
                <strong><i class="fa fa-home"></i> Phòng:</strong> <?php echo $house_no ?>
                (Giá cứng: <?php echo number_format($price, 0, ',', '.') ?> đ)
            </div>
        </div>

        <div class="col-md-12">
            <h5><i class="fa fa-history"></i> Lịch sử chi tiết</h5>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-history">
                    <thead>
                    <tr>
                        <th>Ngày / Mã HĐ</th>
                        <th class="text-right">Chi tiết phí</th>
                        <th class="text-right">Tổng cộng</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $payments = $conn->query("SELECT * FROM payments where tenant_id = $id order by date_created desc");
                    if($payments->num_rows > 0):
                        while($row=$payments->fetch_assoc()):
                            // Tính lại tiền phòng trong quá khứ = Tổng - Điện - Nước
                            $rent_cost = $row['amount'] - $row['cost_electric'] - $row['cost_water'];
                            ?>
                            <tr>
                                <td>
                                    <b><?php echo date("d/m/Y",strtotime($row['date_created'])) ?></b><br>
                                    <span class="badge badge-light border"><?php echo $row['invoice'] ?></span>
                                </td>
                                <td class="text-right">
                                    <div class="detail-row">Tiền phòng: <?php echo number_format($rent_cost, 0, ',', '.') ?> đ</div>
                                    <?php if($row['cost_electric'] > 0): ?>
                                        <div class="detail-row text-warning"><i class="fa fa-bolt"></i> Điện: <?php echo number_format($row['cost_electric'], 0, ',', '.') ?> đ</div>
                                    <?php endif; ?>
                                    <?php if($row['cost_water'] > 0): ?>
                                        <div class="detail-row text-info"><i class="fa fa-tint"></i> Nước: <?php echo number_format($row['cost_water'], 0, ',', '.') ?> đ</div>
                                    <?php endif; ?>
                                </td>
                                <td class='text-right'>
                                    <b style="color: #c62828; font-size: 1.1rem;"><?php echo number_format($row['amount'], 0, ',', '.') ?> đ</b>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">Chưa có giao dịch.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer display p-0 m-0">
    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
</div>