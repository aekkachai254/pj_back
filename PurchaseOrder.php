<?php
// ตรวจสอบว่า request เป็นแบบ OPTIONS หรือไม่ (สำหรับการทำ CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // ตั้งค่า header สำหรับการทำ CORS preflight
    header('Access-Control-Allow-Origin: *'); // หรือตั้งค่าเป็น domain ที่อนุญาต
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); // เพิ่ม Authorization ที่นี่
    exit(); // จบการทำงานหลังจากตั้งค่า header
}

// ตั้งค่า header สำหรับการทำ CORS ใน response
header('Access-Control-Allow-Origin: *'); // หรือตั้งค่าเป็น domain ที่อนุญาต
header('Content-Type: application/json; charset=utf-8');

$database_hostname = 'localhost';
$database_username = 'team'; 
$database_password = 'Te@m1234!';
$database_databasename = 'management';
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);

if(!$connect_management) {
    $response['error'] = 'การเชื่อมต่อฐานข้อมูลล้มเหลว: ' . mysqli_connect_error();
    echo json_encode($response);
} else {
    // รับค่ารหัสสินค้าจาก URL
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // ตรวจสอบว่า $id ไม่ว่างเปล่าก่อนดำเนินการต่อกับคิวรีฐานข้อมูล
    if (empty($id)) {
        $response['error'] = 'พารามิเตอร์ ID หายไป';
        echo json_encode($response);
        exit();
    }

    // คำสั่ง SQL เพื่อตรวจสอบว่ามีรหัสสินค้านี้ในฐานข้อมูลหรือไม่
    $sql = "SELECT * FROM ms_purchaseorder WHERE id = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();   

    // ดึงข้อมูลจากผลลัพธ์
    $row = $result->fetch_assoc();

    if ($row) {
        // แปลงข้อมูลในรูปแบบของ Array
        $Purchaseorder = array(
            'id' => $row['id'],
            'document_no' => $row['document_no'],
            'shop' => $row['shop'],
            'date' => $row['date'],
            'amount_date' => $row['amount_date'],
            'payment_terms' => $row['payment_terms'],
            'price_total' => $row['price_total'],   
            'price_discount' => $row['price_discount'],         
            'cost_total' => $row['cost_total'],
            'cost_text' => $row['cost_text'],
            'status_purchaseorder' => $row['status_purchaseorder'],
        );

        // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
        echo json_encode($Purchaseorder);
    } else {
        // ถ้าไม่มีข้อมูล
        $response['error'] = 'ไม่พบข้อมูล';
        echo json_encode($response);
    }

    // ปิดการเชื่อมต่อกับ MySQL
    $connect_management->close();
}
?>
