<?php
include ('host.php');

// ตรวจสอบว่ามีการส่งคำขอแบบ POST มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $picture_name = $_FILES['picture']['name'];
        $picture_tmp_name = $_FILES['picture']['tmp_name'];
        $picture_destination = $api_website_image.'/delivery/'.$picture_name;

        // ตรวจสอบประเภทของไฟล์ภาพ
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['picture']['type'], $allowed_types)) {
            // ย้ายไฟล์ภาพไปยังโฟลเดอร์ที่ต้องการ
            if (move_uploaded_file($picture_tmp_name, $picture_destination)) {
                // อ่านค่า trip_id จากข้อมูลที่ส่งมา
                $trip_id = isset($_POST['trip_id']) ? $_POST['trip_id'] : null;

                if ($trip_id) {
                    // อัปเดตภาพในตาราง tr_trip_detail
                    $sql_update_picture = "UPDATE tr_trip_detail SET picture = ? WHERE trip_id = ?";
                    $stmt_update_picture = $connect_management->prepare($sql_update_picture);
                    $stmt_update_picture->bind_param("si", $picture_name, $trip_id);
                    if ($stmt_update_picture->execute()) {
                        // อัปเดตสถานะทริปใหม่
                        $sql_update_trip_status = "UPDATE ms_trip SET status_trip = status_trip + 1 WHERE id = ?";
                        $stmt_update_trip_status = $connect_management->prepare($sql_update_trip_status);
                        $stmt_update_trip_status->bind_param("i", $trip_id);
                        if ($stmt_update_trip_status->execute()) {
                            echo json_encode(array('status' => 'success', 'message' => 'Trip status updated successfully.'));
                        } else {
                            echo json_encode(array('status' => 'error', 'message' => 'Failed to update trip status.'));
                        }
                    } else {
                        echo json_encode(array('status' => 'error', 'message' => 'Failed to update picture in tr_trip_detail table.'));
                    }
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Trip ID is missing.'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error uploading picture.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid file type. Only JPEG, JPG, and PNG files are allowed.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No picture uploaded.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>