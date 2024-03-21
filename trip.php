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

$database_hostname = 'teamproject.ddns.net';
$database_username = 'team'; 
$database_password = 'Te@m1234!';
$database_databasename = 'management';
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);
date_default_timezone_set('Asia/Bangkok');

$response = [];

if(!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
    echo json_encode($response);
} else {
    $trips_data = [];

    // สร้างลูปสำหรับดึงข้อมูลจากตาราง ms_trip
    $sql_trip = "SELECT * FROM ms_trip";
    $result_trip = $connect_management->query($sql_trip);

    if ($result_trip) {
        while ($row_trip = $result_trip->fetch_array()) {
            $trip_data = array(
                'id' => $row_trip['id'],
                'name' => $row_trip['name'],
                'date' => $row_trip['date'],
                'car' => $row_trip['car'],
                'create_by' => $row_trip['create_by'],
                'status_trip' => $row_trip['status_trip'],
            );

            // เพิ่มลูปสำหรับดึงข้อมูลจากตาราง tr_trip_detail
            $sql_detail = "SELECT * FROM tr_trip_detail WHERE trip = " . $row_trip['id'];
            $result_detail = $connect_management->query($sql_detail);

            if ($result_detail) {
                $trip_data['tr_trip_detail'] = [];
                while ($row_detail = $result_detail->fetch_array()) {
                    $trip_detail = array(
                        'id' => $row_detail['id'],
                        'purchaseorder' => $row_detail['purchaseorder'],
                        'shop' => $row_detail['shop'],
                        'trip' => $row_detail['trip'],
                        'status_check' => $row_detail['status_check'],
                    );
                    $trip_data['tr_trip_detail'][] = $trip_detail;
                }
            } else {
                $response['error'] = 'Query Failed: ' . $connect_management->error;
                echo json_encode($response);
                exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
            }

            $trips_data['ms_trip'][] = $trip_data; // Move this line inside the while loop
        }
    } else {
        $response['error'] = 'Query Failed: ' . $connect_management->error;
        echo json_encode($response);
        exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
    }

    // ปิดการเชื่อมต่อกับ MySQL
    $connect_management->close();

    // ส่งข้อมูลในรูปแบบ JSON
    echo json_encode($trips_data);
}
?>
