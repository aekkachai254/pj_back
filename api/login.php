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
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (empty($username))
        {
            $response['error'] = 'กรุณากรอกเบอร์โทรศัพท์';
        }
        else
        {
            $sql = "SELECT * FROM ms_personal WHERE telephone = ?";
            $stmt = $connect_management->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_array();
            $stmt->close();
            
            if (empty($row['telephone']))
            {
                $response['error'] = 'ไม่พบบัญชีผู้ใช้นี้';
            }
            elseif ($row['status_account'] == '0')
            {
                $response['error'] = 'บัญชีผู้ใช้นี้ถูกปิด โปรดติดต่อผู้ดูแลระบบ';
            }
            elseif ($row['status_account'] == '1')
            {
                $response['error'] = 'บัญชีนี้ถูกล็อค โปรดติดต่อผู้ดูแลระบบ';
            }
            else
            {
                if (empty($password))
                {
                    $response['error'] = 'กรุณากรอกรหัสผ่าน';
                }
                elseif (password_verify($password, $row['password']))
                {
                    $timestamp = time();
                    $time = date('Y-m-d H:i:s');
                    $sql_lastlogin = "UPDATE ms_personal SET 
                        `status_use` = '1',
                        `status_lastlogin` = '".$time."' 
                        WHERE (`username` = '".$row['username']."')";
                    $query_lastlogin = $connect_management->query($sql_lastlogin);
                    $response['status_use'] = $row['status_use'];
                    $response['message'] = 'เข้าสู่ระบบสำเร็จ โปรดรอสักครู่ ...';
                }
                else
                {
                    $response['error'] = 'รหัสผ่านไม่ถูกต้อง';
                }
            }
        }
    }
    else
    {
        $response['error']  = 'วิธีการร้องขอ METHOD แบบ POST เท่านั้น';
    }
}
// ปิดการเชื่อมต่อกับ MySQL
$connect_management->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>