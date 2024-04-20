<?php
include ('host.php');

// ตรวจสอบว่ามีการเชื่อมต่อสำเร็จหรือไม่
if ($connect_management->connect_error) {
    die('Connection failed: ' . $connect_management->connect_error);
}

// ตั้งเวลาในเขตเวลา Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

$response = [];

if (!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
} else {
    $response['message'] = 'Database Connected!';

    // รับค่ารหัสสินค้าจาก URL
    $product_id = $_GET['product_id'];
    $document_no = $_GET['document_no'];

    // คำสั่ง SQL เพื่อตรวจสอบว่ามีรหัสสินค้านี้ในฐานข้อมูลหรือไม่
    $sql = "SELECT * FROM ms_product WHERE id = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $stmt->close();

    if ($result->num_rows > 0) {
        // คำสั่ง SQL เพื่อตรวจสอบว่ามีรหัสสินค้านี้ในฐานข้อมูลหรือไม่
        $sql_purchase_order = "SELECT * FROM tr_purchaseorder_detail WHERE document_no = ?";
        $stmt_purchase_order = $connect_management->prepare($sql_purchase_order);
        $stmt_purchase_order->bind_param('s', $document_no);
        $stmt_purchase_order->execute();
        $result_purchase_order = $stmt_purchase_order->get_result();
        $stmt_purchase_order->close();

        if ($result_purchase_order->num_rows == 1) {
            echo json_encode(['message' => 'รหัสสินค้านี้ถูกสแกนไปแล้ว']);
        } else {
            $update = "UPDATE tr_purchaseorder_detail SET 
                status_check = '1'
                WHERE document_no = ? AND product = ?";

            // สร้าง statement
            $stmt_update = $connect_management->prepare($update);
            // Bind parameters
            $stmt_update->bind_param('ss', $document_no, $product_id);
            // Execute statement
            $stmt_update->execute();

            $purchaseOrderDetail = array(
                'id' => $row['id'],
                'document_no' => $document_no,
                'product' => $product_id,
                'status_check' => '1',
            );
            echo json_encode(['message' => 'ตรวจสอบรายการสินค้าสำเร็จ']);
            // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
            echo json_encode($purchaseOrderDetail);
        }
    } else {
        // ถ้าไม่มีข้อมูล
        echo json_encode(['message' => 'ไม่พบรหัสสินค้านี้']);
    }

    // ปิดการเชื่อมต่อกับ MySQL
    $connect_management->close();
}
?>
