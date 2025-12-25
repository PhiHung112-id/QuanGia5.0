<?php
session_start();
ini_set('display_errors', 1);

Class Action {
    private $db;

    public function __construct() {
        ob_start();
        include 'db_connect.php';
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
    }

    function login(){
        extract($_POST);
        $qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
        if($qry->num_rows > 0){
            foreach ($qry->fetch_array() as $key => $value) {
                if($key != 'passwors' && !is_numeric($key))
                    $_SESSION['login_'.$key] = $value;
            }
            return 1;
        }else{
            return 3;
        }
    }

    function logout(){
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_user(){
        extract($_POST);
        $data = " name = '$name' ";
        $data .= ", username = '$username' ";
        if(!empty($password))
            $data .= ", password = '".md5($password)."' ";
        $data .= ", type = '$type' ";

        if(empty($id)){
            $chk = $this->db->query("Select * from users where username = '$username' ")->num_rows;
        } else {
            $chk = $this->db->query("Select * from users where username = '$username' and id != '$id' ")->num_rows;
        }

        if($chk > 0){
            return 2;
            exit;
        }

        if(empty($id)){
            $save = $this->db->query("INSERT INTO users set ".$data);
        }else{
            $save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
        }
        if($save){
            return 1;
        }
    }

    function delete_user(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM users where id = ".$id);
        if($delete)
            return 1;
    }

    function save_settings(){
        extract($_POST);
        $data = " name = '".str_replace("'","&#x2019;",$name)."' ";
        $data .= ", email = '$email' ";
        $data .= ", contact = '$contact' ";
        $data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
        if($_FILES['img']['tmp_name'] != ''){
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
            $data .= ", cover_img = '$fname' ";
        }

        $chk = $this->db->query("SELECT * FROM system_settings");
        if($chk->num_rows > 0){
            $save = $this->db->query("UPDATE system_settings set ".$data);
        }else{
            $save = $this->db->query("INSERT INTO system_settings set ".$data);
        }
        if($save){
            $query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
            foreach ($query as $key => $value) {
                if(!is_numeric($key))
                    $_SESSION['system'][$key] = $value;
            }
            return 1;
        }
    }

    function save_category(){
        extract($_POST);
        $data = " name = '$name' ";
        if(empty($id)){
            $save = $this->db->query("INSERT INTO categories set $data");
        }else{
            $save = $this->db->query("UPDATE categories set $data where id = $id");
        }
        if($save)
            return 1;
    }

    function delete_category(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM categories where id = ".$id);
        if($delete){
            return 1;
        }
    }

    function save_complaint(){
        extract($_POST);

        // --- CHECK SỐ ÂM (NEW) ---
        if(isset($cost) && !empty($cost) && $cost < 0){
            return 2; // Trả về lỗi nếu chi phí âm
        }
        // -------------------------

        // Chuẩn bị dữ liệu
        $data = " tenant_id = '$tenant_id' ";
        $data .= ", house_id = '$house_id' ";
        $data .= ", report = '$report' ";
        $data .= ", status = '$status' ";
        $data .= ", cost = '$cost' ";

        if(empty($id)){
            $save = $this->db->query("INSERT INTO complaints set $data");
        }else{
            $save = $this->db->query("UPDATE complaints set $data where id = $id");
        }

        if($save)
            return 1;
    }

    function delete_complaint(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM complaints where id = ".$id);
        if($delete){
            return 1;
        }
    }

    function save_house(){
        extract($_POST);

        // --- CHECK SỐ ÂM (NEW) ---
        if(isset($price) && $price < 0){
            return 3; // Trả về lỗi nếu giá phòng âm
        }
        // -------------------------

        $data = " house_no = '$house_no' ";
        $data .= ", description = '$description' ";
        $data .= ", category_id = '$category_id' ";
        $data .= ", price = '$price' ";

        if(isset($location)){
            $data .= ", location = '".$this->db->real_escape_string($location)."' ";
        }
        if(isset($map_link)){
            $data .= ", map_link = '".$this->db->real_escape_string($map_link)."' ";
        }

        if(empty($id)){
            $chk = $this->db->query("SELECT * FROM houses where house_no = '$house_no'")->num_rows;
            if($chk > 0){
                return 2;
            }

            $save = $this->db->query("INSERT INTO houses set $data");
            if($save){
                $id = $this->db->insert_id;
            }
        }else{
            $save = $this->db->query("UPDATE houses set $data where id = $id");
        }

        if($save){
            if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
                $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
                $move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
                if($move){
                    $this->db->query("UPDATE houses set img_path = '$fname' where id = $id");
                }
            }

            if(isset($_FILES['images']) && count($_FILES['images']['tmp_name']) > 0){
                $total_files = count($_FILES['images']['tmp_name']);
                for($i = 0; $i < $total_files; $i++){
                    if($_FILES['images']['tmp_name'][$i] != ""){
                        $fname_multi = strtotime(date('Y-m-d H:i')).'_'.$i.'_'.$_FILES['images']['name'][$i];
                        $move_multi = move_uploaded_file($_FILES['images']['tmp_name'][$i], 'assets/uploads/'.$fname_multi);
                        if($move_multi){
                            $this->db->query("INSERT INTO house_images (house_id, img_path) VALUES ('$id', '$fname_multi')");
                        }
                    }
                }
            }
            return 1;
        }
    }

    function delete_house(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM houses where id = ".$id);
        if($delete){
            return 1;
        }
    }

    function save_tenant(){
        extract($_POST);
        $data = " firstname = '$firstname' ";
        $data .= ", lastname = '$lastname' ";
        $data .= ", middlename = '$middlename' ";
        $data .= ", email = '$email' ";
        $data .= ", contact = '$contact' ";
        $data .= ", house_id = '$house_id' ";
        $data .= ", date_in = '$date_in' ";
        if(empty($id)){
            $save = $this->db->query("INSERT INTO tenants set $data");
        }else{
            $save = $this->db->query("UPDATE tenants set $data where id = $id");
        }
        if($save)
            return 1;
    }

    function delete_tenant(){
        extract($_POST);
        $delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
        if($delete){
            return 1;
        }
    }

    function signup_customer(){
        extract($_POST);
        $chk = $this->db->query("SELECT * FROM customers where email = '$email' ")->num_rows;
        if($chk > 0){
            return 2;
            exit;
        }
        $pass = md5($password);
        $save = $this->db->query("INSERT INTO customers set name = '$name', email = '$email', password = '$pass', phone = '$phone', address = '$address' ");
        if($save)
            return 1;
    }

    function login_customer(){
        extract($_POST);
        $password = md5($password);
        $qry = $this->db->query("SELECT * FROM customers where email = '$email' and password = '$password' ");
        if($qry->num_rows > 0){
            foreach ($qry->fetch_array() as $key => $value) {
                if($key != 'password' && !is_numeric($key))
                    $_SESSION['login_customer_'.$key] = $value;
            }
            return 1;
        }else{
            return 2;
        }
    }

    function save_booking(){
        extract($_POST);
        $chk = $this->db->query("SELECT * FROM bookings WHERE customer_id = '$customer_id' AND house_id = '$house_id' AND status = 0");
        if($chk->num_rows > 0){
            return 2;
            exit;
        }
        $save = $this->db->query("INSERT INTO bookings set customer_id = '$customer_id', house_id = '$house_id', status = 0");
        if($save) return 1;
    }

    function get_tdetails(){
        extract($_POST);
        $data = array();
        $qry = $this->db->query("SELECT t.*, concat(t.lastname,', ',t.firstname,' ',t.middlename) as name, h.house_no, h.price, h.id as house_id
                          FROM tenants t 
                          INNER JOIN houses h ON h.id = t.house_id 
                          WHERE t.id = {$id} ");

        if($qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            $house_id = $row['house_id'];
            $data['house_no'] = $row['house_no'];
            $data['price'] = number_format($row['price'], 0, ',', '.');
            $data['name'] = ucwords($row['name']);
            $data['rent_started'] = date('d/m/Y',strtotime($row['date_in']));

            $utility = $this->db->query("SELECT * FROM utility_readings WHERE house_id = $house_id ORDER BY date(reading_date) DESC LIMIT 1");
            $e_cost = 0;
            $w_cost = 0;
            if($utility->num_rows > 0){
                $u_row = $utility->fetch_assoc();
                $gia_dien = 3500;
                $gia_nuoc = 15000;
                $e_cost = $u_row['electric'] * $gia_dien;
                $w_cost = $u_row['water'] * $gia_nuoc;
            }
            $data['cost_electric'] = $e_cost;
            $data['cost_water'] = $w_cost;

            $price = $row['price'];
            $date_in = $row['date_in'];
            $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
            $months = floor(($months) / (30*60*60*24));
            $payable = abs($price * $months);
            $data['payable'] = number_format($payable, 0, ',', '.');

            $paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =".$id);
            $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
            $paid = $paid ? $paid : 0;
            $data['paid'] = number_format($paid, 0, ',', '.');

            $last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
            $data['last_payment'] = $last_payment->num_rows > 0 ? date("d/m/Y",strtotime($last_payment->fetch_array()['date_created'])) : 'Chưa đóng';
            $data['outstanding'] = number_format($payable - $paid, 0, ',', '.');
        }
        return json_encode($data);
    }

    function save_utility(){
        extract($_POST);
        $data = " house_id = '$house_id' ";
        $data .= ", electric = '$electric' ";
        $data .= ", water = '$water' ";
        $data .= ", reading_date = '$reading_date' ";

        if(empty($id)){
            $save = $this->db->query("INSERT INTO utility_readings set $data");
        }else{
            $save = $this->db->query("UPDATE utility_readings set $data where id = $id");
        }
        if($save) return 1;
    }

    function delete_utility(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM utility_readings where id = ".$id);
        if($delete) return 1;
    }

    function update_booking_status(){
        extract($_POST);
        $update = $this->db->query("UPDATE bookings set status = '$status' where id = $id");
        if($update && $status == 1){
            $qry = $this->db->query("SELECT b.*, c.name, c.email, c.phone 
                                     FROM bookings b 
                                     INNER JOIN customers c ON c.id = b.customer_id 
                                     WHERE b.id = $id");

            if($qry->num_rows > 0){
                $row = $qry->fetch_assoc();
                $full_name = trim($row['name']);
                $parts = explode(" ", $full_name);
                if(count($parts) > 1){
                    $lastname = array_pop($parts);
                    $firstname = implode(" ", $parts);
                } else {
                    $lastname = $full_name;
                    $firstname = "";
                }
                $data = " firstname = '$firstname' ";
                $data .= ", lastname = '$lastname' ";
                $data .= ", email = '".$row['email']."' ";
                $data .= ", contact = '".$row['phone']."' ";
                $data .= ", house_id = '".$row['house_id']."' ";
                $data .= ", status = 1 ";
                $data .= ", date_in = '".date('Y-m-d')."' ";

                $check = $this->db->query("SELECT * FROM tenants WHERE email = '".$row['email']."' AND house_id = '".$row['house_id']."' AND status = 1");
                if($check->num_rows <= 0){
                    $this->db->query("INSERT INTO tenants set $data");
                }
            }
        }
        if($update){
            return 1;
        }
    }

    function delete_booking(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM bookings where id = ".$id);
        if($delete){
            return 1;
        }
    }

    function save_payment(){
        extract($_POST);

        // --- CHECK SỐ ÂM (NEW) ---
        if(isset($amount) && $amount <= 0){
            return 2; // Trả về lỗi nếu tiền thanh toán <= 0
        }
        // -------------------------

        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
                if(empty($data)){
                    $data .= " $k='$v' ";
                }else{
                    $data .= ", $k='$v' ";
                }
            }
        }
        if(empty($id)){
            $save = $this->db->query("INSERT INTO payments set $data");
        }else{
            $save = $this->db->query("UPDATE payments set $data where id = $id");
        }

        if($save){
            return 1;
        }
    }

    function delete_payment(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM payments where id = ".$id);
        if($delete){
            return 1;
        }
    }
}
?>