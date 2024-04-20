<?php
include ('host.php');

$response = [];

// ตรวจสอบการเชื่อมต่อ
if(!$connect_management)
{
    $response['error'] = 'Database is not connect.';
}
else
{
    $response['message'] = 'Database is connected.';
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'];
        $time = date('Y-m-d H:i:s');

        $update = "UPDATE ms_personal SET 
        status_use = '0', 
        status_lastlogin = '".$time."'
        WHERE telephone = ?";

        // สร้าง statement
        $stmt = $connect_management->prepare($update);
        // Bind parameters
        $stmt->bind_param('s', $username);
        // Execute statement
        $stmt->execute();

        $response['message'] = 'กำลังออกจากระบบ โปรดรอสักครู่ ...';

        // ปิด statement
        $stmt->close();
    }
    else
    {
        $response['error']  = 'วิธีการร้องขอ METHOD แบบ POST เท่านั้น';
    }
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
// ปิดการเชื่อมต่อ MySQLi
$connect_management->close();
?>
