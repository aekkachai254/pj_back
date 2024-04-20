<?php
include ('host.php');

$response = [];

if ($connect_management->connect_error) {
    $response['error'] = 'การเชื่อมต่อฐานข้อมูลล้มเหลว : '.$connect_management->connect_error;
    echo json_encode($response);
    exit;
} else {
    // ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //$data = json_decode(file_get_contents('php://input'), true);
        //$username = $data['username'];

        // รับค่า Username จาก POST
        $username = $_POST['username'];
        if(empty($username))
        {
            $response['message'] = 'ไม่มีการส่งค่า Username โปรดลองใหม่อีกครั้ง';
        }
        else
        {
            $sql_personal = "SELECT * FROM ms_personal WHERE telephone = ?";
            $stmt_personal = $connect_management->prepare($sql_personal);
            $stmt_personal->bind_param('s', $username);
            $stmt_personal->execute();
            $result_personal = $stmt_personal->get_result();
            $row_personal = $result_personal->fetch_array();
            $stmt_personal->close();    
            
            // Query เพื่อตรวจสอบว่ามีการแจ้งเหตุซ้ำหรือไม่
            $sql_check = "SELECT * FROM ms_accident WHERE 
            `create_by` = '".$row_personal['id']."' AND 
            `status_accident` = '1'";
            $query_check = $connect_management->query($sql_check);
            $row_check = $query_check->fetch_array();
            $query_check->close();
            $sql_checkstatus = "SELECT * FROM tbl_status_accident WHERE 
            `id` = '".$row_check['status_check']."'";
            $query_checkstatus = $connect_management->query($sql_checkstatus);
            $row_checkstatus = $query_checkstatus->fetch_array();
            $total_checkstatus = $query_checkstatus->num_rows;
            $query_checkstatus->close();
            if($total_checkstatus >= '1')
            {
                $response['status'] = $row_check['status_accident'];
            }
            else
            {
                $response['status'] = 'N/A';
            }

            // รับค่า POST
            $aoi = $_POST['aoi'] ?? '';
            $subdistrict = $_POST['subdistrict'] ?? '';
            $district = $_POST['district'] ?? '';
            $province = $_POST['province'] ?? '';
            $postcode = $_POST['postcode'] ?? '';
            $country = $_POST['country'] ?? '';
            $currentLocation_Latitude = $_POST['currentlocation_latitude'] ?? '';
            $currentLocation_Longitude = $_POST['currentlocation_longitude'] ?? '';
            $message = $_POST['message'] ?? ''; // เพิ่มการรับค่า message

            // ตรวจสอบค่าว่าง 
            if(empty($aoi) || 
            empty($subdistrict) || 
            empty($district) || 
            empty($province) || 
            empty($postcode) || 
            empty($country) || 
            empty($currentLocation_Latitude) || 
            empty($currentLocation_Longitude) || 
            empty($message)) // เพิ่มการตรวจสอบค่า message
            {
                $response['message'] = 'ข้อมูลตำแหน่งปัจจุบันไม่ครบ โปรดแจ้งเหตุใหม่อีกครั้ง';
            } else {
                if ($query_check->num_rows > 0) {
                    // มีการแจ้งเหตุสำหรับผู้ใช้งานนี้แล้ว
                    $response['message'] = 'คุณมีการแจ้งเหตุไปแล้ว โปรดรอเจ้าหน้าที่รับแจ้งเหตุ';
                } else {
                    // ไม่มีการแจ้งเหตุสำหรับผู้ใช้งานนี้
                    $date = date('Y-m-d H:i:s');
                    // ทำการเพิ่มข้อมูลในฐานข้อมูล
                    $sql_insert = "INSERT INTO ms_accident SET 
                    `date` = '".$date."', 
                    `name` = '".$aoi."', 
                    `address` = '".$subdistrict.' '.$district.' '.$province.' '.$postcode.' '.$country."', 
                    `location_latitude` = '".$currentLocation_Latitude."', 
                    `location_longitude` = '".$currentLocation_Longitude."',
                    `create_by` = '".$row_personal['id']."',
                    `status_accident` = '1',
                    `message` = '".$message."'"; // เพิ่มการเก็บข้อมูล message ในฐานข้อมูล";
                    if ($connect_management->query($sql_insert) === TRUE) {
                        $response['message'] = "แจ้งอุบัติเรียบร้อย โปรดรอเจ้าหน้าที่รับแจ้งเหตุ";
                    } else {
                        $response['error'] = "ไม่สามารถแจ้งเหตุ โปรดลองใหม่อีกครั้ง";
                    }
                }
            }
        }
    } else {
        // ไม่ใช่คำขอ POST
        $response['error'] = "รองรับเฉพาะคำขอแบบ POST เท่านั้น";
    }
}


// ส่งค่ากลับไปยัง Flutter ผ่าน JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>