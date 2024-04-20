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
    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $username = $_GET['username'];
        $sql_personal = "SELECT * FROM ms_personal WHERE telephone = '".$username."'";
        $query_personal = $connect_management->query($sql_personal);
        $row_personal = $query_personal->fetch_array();
        $total_personal = $query_personal->num_rows;
        //$query_personal->clone();
        if($total_personal >= 1)
        {
            $sql_trip = "SELECT * FROM ms_trip WHERE driver = '".$row_personal['id']."'";
            $query_trip = $connect_management->query($sql_trip);
            $total_trip = $query_trip->num_rows;
            if($total_trip >= 1)
            {
                while($row_trip = $query_trip->fetch_array())
                {
                    $date = $row_trip['date'];
                    //$date_time = substr($date,11,8);
                    $date_y = substr($date,0,4)+543;
                    $date_m = substr($date,5,2);
                    $date_d = substr($date,8,2);				
                    $date = $date_d."/".$date_m."/".$date_y;
                    $trip_data[] = [
                        'id' => $row_trip['id'],
                        'name' => $row_trip['name'],
                        'date' => $date,
                        'status_trip' => $row_trip['status_trip'],
                    ];
                }
                echo json_encode($trip_data, JSON_UNESCAPED_UNICODE);
            }
            else
            {
                $response['error'] = 'ไม่พบข้อมูลรายการเดินทาง';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            $response['error'] = 'ไม่พบผู้ใช้';
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
