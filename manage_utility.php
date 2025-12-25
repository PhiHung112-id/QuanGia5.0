<?php include 'db_connect.php';
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM utility_readings where id=".$_GET['id'])->fetch_array();
    foreach($qry as $k => $val){ $$k = $val; }
}
?>
<div class="container-fluid">
    <form action="" id="manage-utility">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

        <div class="form-group">
            <label class="control-label">Chọn Phòng</label>
            <select name="house_id" id="" class="custom-select select2">
                <option value=""></option>
                <?php
                $room = $conn->query("SELECT * FROM houses order by house_no asc");
                while($row=$room->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? "selected" : "" ?>>Phòng <?php echo $row['house_no'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label">Ngày ghi sổ</label>
            <input type="date" class="form-control" name="reading_date" value="<?php echo isset($reading_date) ? date('Y-m-d',strtotime($reading_date)) : date('Y-m-d') ?>">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Chỉ số Điện (Mới)</label>
                    <input type="number" class="form-control text-right" name="electric" value="<?php echo isset($electric) ? $electric : 0 ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Chỉ số Nước (Mới)</label>
                    <input type="number" class="form-control text-right" name="water" value="<?php echo isset($water) ? $water : 0 ?>">
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('.select2').select2({ placeholder:"Chọn phòng", width:"100%" });
    $('#manage-utility').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_utility',
            data: new FormData($(this)[0]),
            cache: false, contentType: false, processData: false, method: 'POST', type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Lưu thành công",'success')
                    setTimeout(function(){ location.reload() },1000)
                }
            }
        })
    })
</script>