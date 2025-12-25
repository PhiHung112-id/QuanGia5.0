<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_category"){
	$save = $crud->save_category();
	if($save)
		echo $save;
}

if($action == "delete_category"){
	$delete = $crud->delete_category();
	if($delete)
		echo $delete;
}
if($action == "save_house"){
	$save = $crud->save_house();
	if($save)
		echo $save;
}
if($action == "delete_house"){
	$save = $crud->delete_house();
	if($save)
		echo $save;
}

if($action == "save_tenant"){
	$save = $crud->save_tenant();
	if($save)
		echo $save;
}
if($action == "delete_tenant"){
	$save = $crud->delete_tenant();
	if($save)
		echo $save;
}
if($action == "get_tdetails"){
	$get = $crud->get_tdetails();
	if($get)
		echo $get;
}

if($action == "save_payment"){
	$save = $crud->save_payment();
	if($save)
		echo $save;
}
if($action == "delete_payment"){
	$save = $crud->delete_payment();
	if($save)
		echo $save;
}

// --- ĐÂY LÀ PHẦN MỚI THÊM VÀO ---
if($action == "save_complaint"){
	$save = $crud->save_complaint();
	if($save)
		echo $save;
}
if($action == "delete_complaint"){
	$save = $crud->delete_complaint();
	if($save)
		echo $save;
}
// ---------------------------------
// --- PHẦN KHÁCH HÀNG (CUSTOMER) ---
// --- GỌI HÀM XỬ LÝ KHÁCH HÀNG ---

if($action == 'signup_customer'){
    $save = $crud->signup_customer();
    if($save) echo $save;
}

if($action == 'login_customer'){
    $login = $crud->login_customer();
    if($login) echo $login;
}

if($action == 'save_booking'){
    $save = $crud->save_booking();
    if($save) echo $save;
}

if($action == 'logout_customer'){
    session_destroy();
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
    }
    header("location:customer/index.php");
}
// Trong file ajax.php
if($action == 'update_booking_status'){
    $save = $crud->update_booking_status();
    if($save) echo $save;
}
if($action == 'delete_booking'){
    $save = $crud->delete_booking();
    if($save) echo $save;
}

if($action == 'save_utility'){
    $save = $crud->save_utility();
    if($save) echo $save;
}
if($action == 'delete_utility'){
    $save = $crud->delete_utility();
    if($save) echo $save;
}
ob_end_flush();
?>