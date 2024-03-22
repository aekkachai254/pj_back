<?php
// ตรวจสอบว่า request เป็นแบบ OPTIONS หรือไม่ (สำหรับการทำ CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // ตั้งค่า header สำหรับการทำ CORS preflight
    header('Access-Control-Allow-Origin: *'); // หรือตั้งค่าเป็น domain ที่อนุญาต
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); // เพิ่ม Authorization ที่นี่
}

// ตั้งค่า header สำหรับการทำ CORS ใน response
header('Access-Control-Allow-Origin: *'); // หรือตั้งค่าเป็น domain ที่อนุญาต
header('Content-Type: application/json; charset=utf-8');

$database_hostname = 'localhost';
$database_username = 'team'; 
$database_password = 'Te@m1234!';
$database_databasename = 'management';
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);
date_default_timezone_set('Asia/Bangkok');

$response = [];

if(!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
} else {
    $response['message'] = 'Database Connected!';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    // สร้าง SQL query เพื่อดึงข้อมูลผู้ใช้
    $sql = "SELECT * FROM ms_personal WHERE telephone = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $stmt->close();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // วันเกิด และ อายุ
        $inbirth = $row['birthday'];
        $birth_y = substr($inbirth,0,4)+543;
        $birth_m = substr($inbirth,5,2);
        $birth_d = substr($inbirth,-2,2);				
        $age = date('Y')-substr($inbirth,0,4);		 //แปลงวันเดือนปีเกิดจาก database
        $sql_month = "SELECT * FROM tbl_month WHERE id = '".$birth_m."'"; 
        $query_month = $connect_management->query($sql_month);
        $row_month = $query_month->fetch_array();
        $query_month->close();    
        $birthday = $birth_d.' '.$row_month['name'].' '.$birth_y.' อายุ '.$age.' ปี';    

        // ตำแหน่ง
        $sql_position = "SELECT * FROM tbl_position WHERE id = '".$row['position']."'"; 
        $query_position = $connect_management->query($sql_position);
        $row_position = $query_position->fetch_array();
        $query_position->close();    

        // แปลงข้อมูลในรูปแบบของ Array
        // สร้าง Array สำหรับเก็บข้อมูลผู้ใช้
        $userProfile = array(
            'id' => $row['id'],
            'titlename' => $row['titlename'],
            'firstname' => $row['firstname'],
            'surname' => $row['surname'],
            'birthday' => $birthday,
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'position' => $row_position['name']
        );

        // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
        echo json_encode($userProfile);
    } else {
        // ถ้าไม่มีข้อมูล
        echo "No user found";
    }
}
// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>