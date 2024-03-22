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
header("Content-Type: application/json");

$database_hostname = 'localhost';
$database_username = 'team'; 
$database_password = 'Te@m1234!';
$database_databasename = 'management';

// สร้างการเชื่อมต่อ MySQLi
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);

// ตรวจสอบการเชื่อมต่อ
if ($connect_management->connect_error) {
    die("Connection failed: " . $connect_management->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'];
    $time = date('Y-m-d H:i:s');

    $update = "UPDATE ms_personal SET 
    status_use = '0', 
    status_lastlogin = ?
    WHERE telephone = ?";

    // สร้าง statement
    $stmt = $connect_management->prepare($update);
    // Bind parameters
    $stmt->bind_param('ss', $time, $username);
    // Execute statement
    $stmt->execute();

    echo json_encode(['message' => 'ออกจากระบบสำเร็จ โปรดรอสักครู่ ...']);

    // ปิด statement
    $stmt->close();
} else {
    echo json_encode(['message' => 'วิธีการร้องขอไม่ถูกต้อง']);
}

// ปิดการเชื่อมต่อ MySQLi
$connect_management->close();
?>
