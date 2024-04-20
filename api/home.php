<?php
include ('host.php');

$response = [];

if(!$connect_management)
{
    $response['error'] = 'Database is not connect.';
}
else
{
    $response['message'] = 'Database is connected.';
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        $sql = "SELECT * FROM ms_personal WHERE telephone = ?";
        $stmt = $connect_management->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
        $stmt->close();

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

            $userProfile = array(
                'titlename' => $row['titlename'],
                'firstname' => $row['firstname'],
                'surname' => $row['surname'], 
                'picture' => $picture
            );
            // ส่งค่าในรูปแบบ JSON กลับไปยัง Dart application
            echo json_encode($userProfile, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $response['error']  = 'ไม่พบผู้ใช้';
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
