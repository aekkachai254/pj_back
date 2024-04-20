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
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        // สร้าง SQL query เพื่อดึงข้อมูลผู้ใช้
        $sql = "SELECT * FROM ms_personal WHERE telephone = ?";
        $stmt = $connect_management->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
        $stmt->close();

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($result->num_rows > 0)
        {
            if(empty($row['picture']))
            {
                $picture = $api_website_image.'/person.png';
            }
            else
            {
                $picture = $api_website_image.'/person/'.$row['picture'];
            }
            // วันเกิด และ อายุ
            $inbirth = $row['birthday'];
            $birth_y = substr($inbirth,0,4)+543;
            $birth_m = substr($inbirth,5,2);
            $birth_d = substr($inbirth,-2,2);				
            $age = date('Y')-substr($inbirth,0,4);		 //แปลงวันเดือนปีเกิดจาก database
            $sql_month = "SELECT * FROM tbl_month WHERE id = '".$birth_m."'"; 
            $query_month = $connect_management->query($sql_month);
            $row_month = $query_month->fetch_array();
            $query_month->close();    
            $birthday = $birth_d.' '.$row_month['name'].' '.$birth_y.' อายุ '.$age.' ปี';    

            // ตำแหน่ง
            $sql_position = "SELECT * FROM tbl_position WHERE id = '".$row['position']."'"; 
            $query_position = $connect_management->query($sql_position);
            $row_position = $query_position->fetch_array();
            $query_position->close();

            // แปลงข้อมูลในรูปแบบของ Array
            // สร้าง Array สำหรับเก็บข้อมูลผู้ใช้
            $userProfile = array (
                'id' => $row['id'],
                'titlename' => $row['titlename'],
                'firstname' => $row['firstname'],
                'surname' => $row['surname'],
                'picture' => $picture,
                'birthday' => $birthday,
                'email' => $row['email'],
                'telephone' => $row['telephone'],
                'position' => $row_position['name']
            );
            // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
            echo json_encode($userProfile, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $response['error']  = 'ไม่พบข้อมูลผู้ใช้';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }
    else
    {
        $response['error']  = 'วิธีการร้องขอ METHOD แบบ GET เท่านั้น';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();
?>