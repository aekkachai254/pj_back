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

if (!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
    echo json_encode($response);
} else {
    $response['message'] = 'Database Connected!';
    // รับค่ารหัสสินค้าจาก URL
    $id = $_GET['id'];
    // คำสั่ง SQL เพื่อตรวจสอบว่ามีรหัสสินค้านี้ในฐานข้อมูลหรือไม่
    $sql = "SELECT * FROM ms_product WHERE id = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $stmt->close();

    if ($result->num_rows > 0) {
        $sql_detail = "SELECT * FROM tr_purchaseorder_detail WHERE product = ?";
        $stmt_detail = $connect_management->prepare($sql_detail);
        $stmt_detail->bind_param('s', $row['id']);
        $stmt_detail->execute();
        $result_detail = $stmt_detail->get_result();
        $row_detail = $result_detail->fetch_array();
        $stmt_detail->close();

        $product = array(
            'picture_1' => $row['picture_1'],
            'name' => $row['name'],
            'product_amount' => $row_detail['product_amount'],
            'package' => $row['package'],
            'price_package' => $row['price_package'],
            'size' => $row['size'],
        );

        // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
        echo json_encode($product);
    } else {
        // ถ้าไม่มีข้อมูล
        $response['error'] = 'No product found';
        echo json_encode($response);
    }
}

// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>
