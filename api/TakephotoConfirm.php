<?php
include ('host.php');
// ตรวจสอบว่ามีคำขอ GET ที่ส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // ตรวจสอบว่ามีค่า GET ที่รับมาหรือไม่
    if(isset($_GET['trip_id']) && isset($_GET['purchaseorder_id'])) {

        // สร้างการเชื่อมต่อ
        $conn = new mysqli($servername, $username, $password, $dbname);

        // ตรวจสอบการเชื่อมต่อ
        if ($connect_management->$connect_management) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->$connect_management);
        }

        // กำหนดค่า trip_id และ purchaseorder_id จาก GET
        $trip_id = $_GET['trip_id'];
        $purchaseorder_id = $_GET['purchaseorder_id'];

        // สร้างคำสั่ง SQL เพื่อเลือกข้อมูล
        $sql = "SELECT picture_datetime FROM tr_tripdetail WHERE trip_id = '$trip_id' AND purchaseorder_id = '$purchaseorder_id'";

        // ประมวลผลคำสั่ง SQL
        $result = $connect_management->query($sql);

        // ตรวจสอบว่ามีข้อมูลที่ได้รับหรือไม่
        if ($result->num_rows > 0) {
            // แสดงข้อมูลทีละแถว
            while($row = $result->fetch_array()) {
                echo "picture_datetime: " . $row["picture_datetime"]. "<br>";
            }
        } else {
            echo "ไม่พบข้อมูล";
        }

        // ปิดการเชื่อมต่อ
        $connect_management->close();
    } else {
        echo "โปรดระบุ trip_id และ purchaseorder_id ในคำขอ GET";
    }
} else {
    echo "การเข้าถึงไม่ถูกต้อง";
}
?>
