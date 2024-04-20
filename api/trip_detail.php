<?php
include ('host.php');

$response = [];

if (!$connect_management)
{
    $response['database_message'] = 'Database is not connect.';
}
else
{
    $response['database_message'] = 'Database is connected.';
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $trip_data = [];
        $sql_trip = "SELECT * FROM ms_trip Where id = '".$_GET['trip_id']."'" ;
        $result_trip = $connect_management->query($sql_trip);
        $total_trip = $result_trip->num_rows;
        if ($total_trip > 0)
        {
            while ($row_trip = $result_trip->fetch_array())
            {
                $trip_data[] = [
                    'id' => $row_trip['id'],
                    'name' => $row_trip['name'],
                    'date' => $row_trip['date'],
                    'driver' => [],
                    'car' => [],
                    'trip_detail' => []
                ];
                // เพิ่มข้อมูลคนขับ (ms_personal)
                $sql_personal = "SELECT * FROM ms_personal WHERE id = " . $row_trip['driver'];
                $result_personal = $connect_management->query($sql_personal);
                if ($result_personal) {
                    while ($row_personal = $result_personal->fetch_array())
                    {
                        if(empty($row_personal['picture']))
                        {
                            $driver_picture = $api_website_image.'/person.png';
                        }
                        else
                        {
                            $driver_picture = $api_website_image.'/person/'.$row_personal['picture'];
                        }
                        $personal_data = [
                            'id' => $row_personal['id'],
                            'picture' => $driver_picture,
                            'titlename' => $row_personal['titlename'],
                            'firstname' => $row_personal['firstname'],
                            'surname' => $row_personal['surname'],
                            'telephone' => $row_personal['telephone'],
                        ];
                        $trip_data[count($trip_data) - 1]['driver'] = $personal_data;
                    }
                }
                else
                {
                    $response['error'] = 'Unable to query from ms_personal';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
                }

                // เพิ่มข้อมูลรถ (ms_car)
                $sql_car = "SELECT * FROM ms_car WHERE id = " . $row_trip['car'];
                $result_car = $connect_management->query($sql_car);
                if ($result_car)
                {
                    while ($row_car = $result_car->fetch_array())
                    {
                        if(empty($row_car['picture']))
                        {
                            $car_picture = $api_website_image.'/car.png';
                        }
                        else
                        {
                            $car_picture = $api_website_image.'/car/'.$row_car['picture'];
                        }
                        $car_data = [
                            'id' => $row_car['id'],
                            'picture' => $car_picture,
                            'brand' => $row_car['brand'],
                            'license' => $row_car['license'],
                            'color' => $row_car['color'],
                            'service_life' => $row_car['service_life'],
                        ];
                        // เพิ่มข้อมูลรถเข้าไปใน $trip_data['ms_car']
                        $trip_data[count($trip_data) - 1]['car'] = $car_data;
                    }
                }
                else
                {
                    $response['error'] = 'Unable to query from ms_car';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
                }

                // เพิ่มข้อมูลรายละเอียดการเดินทาง (tr_trip_detail)
                $sql_detail = "SELECT * FROM tr_trip_detail WHERE trip = " . $row_trip['id'];
                $result_detail = $connect_management->query($sql_detail);
                if ($result_detail)
                {
                    while ($row_detail = $result_detail->fetch_array())
                    {
                        $sql_status_check = "SELECT * FROM tbl_status_check WHERE id = " . $row_detail['status_check'];
                        $result_status_check = $connect_management->query($sql_status_check);
                        $row_status_check = $result_status_check->fetch_array();
                        $trip_detail = [
                            'id' => $row_detail['id'],
                            'purchaseorder' => $row_detail['purchaseorder'],
                            'shop' => [],
                            'trip' => $row_detail['trip'],
                            'status_check' => $row_status_check['name']
                        ];
                        $sql_shop = "SELECT * FROM ms_shop WHERE id = " . $row_detail['shop'];
                        $result_shop = $connect_management->query($sql_shop);
                        if ($result_shop)
                        {
                            while ($row_shop = $result_shop->fetch_array())
                            {
                                $shop_data = [
                                    'id' => $row_shop['id'],    
                                    'picture' => $row_shop['picture'],
                                    'name' => $row_shop['name'],
                                    'address' => $row_shop['address']
                                ];
                                $trip_detail['shop'] = $shop_data;
                            }
                        }
                        $trip_data[count($trip_data) - 1]['trip_detail'][] = $trip_detail;
                    }
                }
                else
                {
                    $response['error'] = 'Unable to query from tr_trip_detail';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
                }
            }
            // ส่งข้อมูลในรูปแบบ JSON
            echo json_encode($trip_data);
        }
        else
        {
            $response['error'] = 'Unable to query from ms_trip';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit; // ถ้า query ล้มเหลวให้จบการทำงานทันที
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
