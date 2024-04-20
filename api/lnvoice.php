<?php
include('host.php');

$response = array();

if (!$connect_management) {
    $response['database_message'] = 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    $response['database_message'] = 'เชื่อมต่อฐานข้อมูลเรียบร้อยแล้ว';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $purchaseorder_id = isset($_GET['purchaseorder_id']) ? $_GET['purchaseorder_id'] : '';
        $username = isset($_GET['username']) ? $_GET['username'] : '';

        if (empty($purchaseorder_id)) {
            $response['error'] = 'รหัสใบสั่งซื้อไม่ถูกต้อง';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            $sql_purchaseorder = "SELECT * FROM ms_purchaseorder WHERE id = ?";
            $stmt_purchaseorder = $connect_management->prepare($sql_purchaseorder);
            if (!$stmt_purchaseorder) {
                $response['error'] = 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                $connect_management->close();
                exit();
            }
            $stmt_purchaseorder->bind_param('s', $purchaseorder_id);
            $stmt_purchaseorder->execute();
            $result_purchaseorder = $stmt_purchaseorder->get_result();
            $row_purchaseorder = $result_purchaseorder->fetch_array();
            $stmt_purchaseorder->close();

            if ($row_purchaseorder) {
                if (empty($row_purchaseorder['picture_invoice'])) {
                    $picture_invoice = $api_website_image . '/invoice.png';
                } else {
                    $picture_invoice = $api_website_image . '/invoice/' . $row_purchaseorder['picture_invoice'];
                }
                $response['invoice'] = array(
                    'picture_invoice' => $picture_invoice,
                );
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $response['error'] = 'ไม่พบใบสั่งซื้อ';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        }
    } else {
        $response['error'] = 'รูปแบบการร้องขอต้องเป็น GET เท่านั้น';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}

$connect_management->close();
