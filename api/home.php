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
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // เพิ่มบรรทัดนี้

$database_hostname = 'localhost';
$database_username = 'team'; 
$database_password = 'Te@m1234!';
$database_databasename = 'management';
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);
date_default_timezone_set('Asia/Bangkok');

$response = [];

if(!$connect_management) {
    $response['error'] = 'Database Connect Failed: ' . mysqli_connect_error();
    http_response_code(500);
    echo json_encode($response);
} else {
    $response['message'] = 'Database Connected!';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $sql = "SELECT * FROM ms_personal WHERE telephone = ?";
    $stmt = $connect_management->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array();
    $stmt->close();

    if ($result->num_rows > 0) {
        if($row['picture'] == 1) {
            $picture = 'http://teamproject.ddns.net/project/assets/images/person/'.$row['id'].'.jpg';
        } else {
            $picture = 'http://teamproject.ddns.net/application/assets/images/person.png';
        }

        $userProfile = array(
            'titlename' => $row['titlename'],
            'firstname' => $row['firstname'],
            'surname' => $row['surname'], 
            'picture' => $picture
        );

        echo json_encode($userProfile);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No user found"));
    }
}

$connect_management->close();
?>
