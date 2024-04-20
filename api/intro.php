<?php
include ('host.php');

$response = [];

if(!$connect_management)
{
    $response['error'] = 'Database is not connect.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
else
{
    $response['message'] = 'Database is connected.';
    $id = 1;
    // สร้าง SQL query เพื่อดึงข้อมูล
    $sql = "SELECT * FROM ms_system WHERE id = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $stmt->close();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows >= 1)
    {
        // แปลงข้อมูลในรูปแบบของ Array
        // สร้าง Array สำหรับเก็บข้อมูล
        $dataSystem = array(
            'logo' => $api_website_image.'/'.$row['logo'], 
            'name' => $row['name']
        );

        // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
        echo json_encode($dataSystem, JSON_UNESCAPED_UNICODE);
    }
    else
    {
        // ถ้าไม่มีข้อมูล
        $response['error'] = 'ไม่พบข้อมูล';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>
