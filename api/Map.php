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

// เชื่อมต่อกับฐานข้อมูล MySQL
$database_hostname = 'localhost';
$database_username = 'team';
$database_password = 'Te@m1234!';
$database_databasename = 'management';
$connect_management = new mysqli($database_hostname, $database_username, $database_password, $database_databasename);

// ตรวจสอบว่ามีการเชื่อมต่อสำเร็จหรือไม่
if ($connect_management->connect_error) {
    die('Connection failed: ' . $connect_management->connect_error);
}

// ตั้งเวลาในเขตเวลา Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

// ข้อมูลสถานที่
$places = [
    [
        'name' => 'สถานที่ A',
        'address' => '1 ม.2 ซ.3 ถ.4 ต.5 อ.6 จ.7 12345',
       
    ],
    [
        'name' => 'สถานที่ B',
        'address' => '1 ม.2 ซ.3 ถ.4 ต.5 อ.6 จ.7 12345',
    ],
    [
        'name' => 'สถานที่ C',
        'address' => '1 ม.2 ซ.3 ถ.4 ต.5 อ.6 จ.7 12345',
    ],
    [
        'name' => 'สถานที่ D',
        'address' => '1 ม.2 ซ.3 ถ.4 ต.5 อ.6 จ.7 12345',
        
    ],
];

// สร้าง response ในรูปแบบ JSON
$responseData = [];

foreach ($places as $place) {
    // ทำการ encode ที่อยู่เพื่อให้เป็นรูปแบบที่สามารถส่งใน URL ได้
    $encodedAddress = urlencode($place['address']);

    // สร้าง URL สำหรับ Google Geocoding API
    $googleMapsApiKey = 'AIzaSyAJeJV4sTe3zgEP-l8oJyVImWo-n51SBiw'; // แทนที่ YOUR_GOOGLE_MAPS_API_KEY ด้วย API Key 
    const requestUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${AIzaSyAJeJV4sTe3zgEP-l8oJyVImWo-n51SBiw}`; 
}";

    // ใช้ cURL ในการเรียก Google Geocoding API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $geoResponse = curl_exec($ch);
    curl_close($ch);

    // ตรวจสอบว่ามี response หรือไม่
    if ($geoResponse === FALSE) {
        // กรณีไม่สามารถเรียก API ได้
        $responseData[] = [
            'name' => $place['id'],
            'status' => 'error',
            'message' => 'Failed to connect to Google Geocoding API.'
        ];
    } else {
        // ทำการ decode JSON response เป็น associative array
        $geoData = json_decode($geoResponse, true);

        // เช็คว่ามีข้อมูลตำแหน่งหรือไม่
        if ($geoData['status'] == 'OK') {
            // ดึงข้อมูลตำแหน่ง (latitude, longitude)
            $location = $geoData['results'][0]['geometry']['location'];

            // เพิ่มข้อมูลสถานที่ลงใน response
            $placeData = [
                'name' => $place['name'],
                'address' => $place['address'],
                'location' => $location,
                'phone' => $place['phone']
            ];

            $responseData[] = $placeData;
        } else {
            // กรณี API ไม่สำเร็จ
            $responseData[] = [
                'id' => $place['name'],
                'status' => 'error',
                'message' => 'Google Geocoding API request failed. ' . $geoData['error_message']
            ];
        }
    }
}

// แสดงผล response ในรูปแบบ JSON
echo json_encode($responseData);
?>