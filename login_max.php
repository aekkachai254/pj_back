<?php
include ('connect/management.php');
$username = @$_POST['username'];
$password = trim(@$_POST['password']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
if(empty($username) || empty($password))
{
	header("location:index.php?warning=1");
}
else
{
	$sql = "SELECT * FROM ms_personal WHERE telephone = '".$username."'";
	$query = $connect_management->query($sql);
	$row = $query->fetch_array();
	//$total = $query->num_rows;
	$query->close();
	if(empty($row['username']))
	{
		//header("location:index.php?warning=2");
		echo json_encode('ไม่พบบัญชีผู้ใช้นี้');
	}
	elseif($row['status_account'] == '0')
	{
		//header("location:index.php?warning=3");
		echo json_encode('บัญชีผู้ใช้นี้ถูกปิด โปรดติดต่อผู้ดูแลระบบ');
	}
	elseif($row['status_account'] == '1')
	{
		//header("location:index.php?warning=4");
		echo json_encode('บัญชีนี้ถูกล็อค โปรดติดต่อผู้ดูแลระบบ');
	}
	elseif($row['telephone'] == $username && password_verify($password, $row['password']))
	{
		$timestamp = time();
		$time = date('Y-m-d H:i:s');
		session_start();
		$_SESSION['username'] = $row['telephone'];
		$_SESSION['password'] = $row['password'];
		$sql_lastlogin = "UPDATE ms_personal SET 
			`status_use` = '1',
			`status_lastlogin` = '".$time."' 
		WHERE (`username` = '".$row['username']."')";
		$query_lastlogin = $connect_management->query($sql_lastlogin);
		//header("location:index.php?warning=6");
		echo json_encode('เข้าสู๋ระบบสำเร็จ โปรดรอสักครู่ ...');
	}
	else
	{
		//header("location:index.php?warning=5");
		echo json_encode('รหัสผ่านไม่ถูกต้อง');
	}
}
$connect_management->close();
?>