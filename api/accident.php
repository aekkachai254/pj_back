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

$response = [];

if (!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
    echo json_encode($response);
} else {
    $response['message'] = 'Database Connected!';
    
    // ตรวจสอบประเภทของคำขอ (GET, POST)
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method === 'GET') {
        // รับค่ารหัสสินค้าจาก URL
        $id = $_GET['id'];

        // คำสั่ง SQL เพื่อตรวจสอบว่ามีรหัสสินค้านี้ในฐานข้อมูลหรือไม่
        $sql = "SELECT * FROM ms_accident WHERE id = ?";
        $stmt = $connect_management->prepare($sql);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $product = array(
                'id' => $row['id'],
                'datetime' => $row['datetime'],
                'location_latitude' => $row['location_latitude'],
                'location_longitude' => $row['location_longitude'],
            );
            // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
            echo json_encode($product);
        } else {
            // ถ้าไม่มีข้อมูล
            $response['error'] = 'No product found';
            echo json_encode($response);
        }
    } elseif ($request_method === 'POST') {
        // ดึงข้อมูลจากตัวแปร JSON ที่ส่งมา
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        // ตรวจสอบว่าข้อมูลถูกส่งมาในรูปแบบที่ถูกต้องหรือไม่
        if (json_last_error() === JSON_ERROR_NONE) {
            // ดึงข้อมูลจากตัวแปรที่ส่งมา
            $id = $data['id'];
            $datetime = $data['datetime'];
            $location_latitude = $data['location_latitude'];
            $location_longitude = $data['location_longitude'];

            // ตรวจสอบว่ามี id ซ้ำหรือไม่
            $check_sql = "SELECT * FROM ms_accident WHERE id = ?";
            $check_stmt = $connect_management->prepare($check_sql);
            $check_stmt->bind_param('s', $id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_stmt->close();

            if ($check_result->num_rows > 0) {
                // ถ้ามี id ซ้ำ ให้ทำการอัปเดตข้อมูล
                $update_sql = "UPDATE ms_accident SET datetime = ?, location_latitude = ?, location_longitude = ? WHERE id = ?";
                $update_stmt = $connect_management->prepare($update_sql);
                $update_stmt->bind_param('ssdd', $datetime, $location_latitude, $location_longitude, $id);

                if ($update_stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'User updated successfully';
                } else {
                    $response['error'] = 'Error updating user: ' . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                // ถ้าไม่มี id ซ้ำ ให้ทำการเพิ่มข้อมูล
                $insert_sql = "INSERT INTO ms_accident (id, datetime, location_latitude, location_longitude) VALUES (?, ?, ?, ?)";
                $insert_stmt = $connect_management->prepare($insert_sql);
                $insert_stmt->bind_param('ssdd', $id, $datetime, $location_latitude, $location_longitude);

                if ($insert_stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'User added successfully';
                } else {
                    $response['error'] = 'Error adding user: ' . $insert_stmt->error;
                }

                $insert_stmt->close();
            }
        } else {
            $response['error'] = 'Invalid JSON format';
        }

        // ส่ง response กลับในรูปแบบ JSON
        echo json_encode($response);
    }
}

// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>
