<?php
include('host.php');

$response = [];

if (!$connect_management)
{
    $response['database_message'] = 'Database is not connect.';
    echo json_encode($response);
}
else
{
    $response['database_message'] = 'Database is connected.';
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

    if ($result->num_rows > 0)
    {
        $sql_detail = "SELECT * FROM tr_purchaseorder_detail WHERE product = ?";
        $stmt_detail = $connect_management->prepare($sql_detail);
        $stmt_detail->bind_param('s', $row['id']);
        $stmt_detail->execute();
        $result_detail = $stmt_detail->get_result();
        $row_detail = $result_detail->fetch_array();
        $stmt_detail->close();

        $product = array (
            'picture_1' => $row['picture_1'],
            'name' => $row['name'],
            'product_amount' => $row_detail['product_amount'],
            'package' => $row['package'],
            'price_package' => $row['price_package'],
            'size' => $row['size'],
        );

        // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
        echo json_encode($product);
    }
    else
    {
        // ถ้าไม่มีข้อมูล
        $response['error'] = 'No product found';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>
